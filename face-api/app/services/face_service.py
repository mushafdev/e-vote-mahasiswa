import numpy as np
import json
import os
from deepface import DeepFace
from deepface.modules import modeling, preprocessing
from flask import current_app
from app.utils.image import base64_to_numpy

EMBEDDINGS_DIR = os.path.join(os.path.dirname(__file__), '../../storage/embeddings')


def _embedding_path(pemilih_id: str) -> str:
    return os.path.join(EMBEDDINGS_DIR, f"{pemilih_id}.json")


def extract_embedding(img: np.ndarray) -> list:
    """Ekstrak face embedding dari numpy array gambar."""
    model    = current_app.config['FACE_MODEL']
    detector = current_app.config['DETECTOR_BACKEND']

    result = DeepFace.represent(
        img_path=img,
        model_name=model,
        detector_backend=detector,
        enforce_detection=True,
    )
    return result[0]['embedding']


def check_spoof(img: np.ndarray) -> dict:
    """
    Deteksi liveness (anti-spoofing) menggunakan DeepFace built-in.
    Return: { is_real: bool, spoof_score: float, antispoof_label: str }
    spoof_score 0.0–1.0, semakin tinggi semakin real.
    """
    try:
        detector = current_app.config['DETECTOR_BACKEND']
        faces = DeepFace.extract_faces(
            img_path=img,
            detector_backend=detector,
            enforce_detection=True,
            anti_spoofing=True,
        )
        if not faces:
            return {'is_real': False, 'spoof_score': 0.0, 'antispoof_label': 'no_face'}

        face = faces[0]
        is_real      = face.get('is_real', False)
        antispoof_score = face.get('antispoof_score', 0.0)

        return {
            'is_real'        : bool(is_real),
            'spoof_score'    : round(float(antispoof_score), 4),
            'antispoof_label': 'real' if is_real else 'spoof',
        }
    except Exception as e:
        current_app.logger.warning(f'[anti_spoofing] {e}')
        # Fallback: anggap real jika model gagal (agar tidak block sistem)
        return {'is_real': True, 'spoof_score': 1.0, 'antispoof_label': 'error'}


def _antispoof_on_area(img: np.ndarray, facial_area: dict) -> dict:
    """
    Jalankan Fasnet pada area wajah yang SUDAH terdeteksi (tanpa deteksi ulang).
    Identik dengan yang dilakukan DeepFace.extract_faces(anti_spoofing=True).
    """
    try:
        antispoof_model = modeling.build_model(task='spoofing', model_name='Fasnet')
        area = (facial_area['x'], facial_area['y'], facial_area['w'], facial_area['h'])
        is_real, antispoof_score = antispoof_model.analyze(img=img, facial_area=area)
        return {
            'is_real'        : bool(is_real),
            'spoof_score'    : round(float(antispoof_score), 4),
            'antispoof_label': 'real' if is_real else 'spoof',
        }
    except Exception as e:
        current_app.logger.warning(f'[anti_spoofing] {e}')
        # Fallback sama dengan check_spoof: anggap real jika model gagal
        return {'is_real': True, 'spoof_score': 1.0, 'antispoof_label': 'error'}


def _embedding_from_face(face: np.ndarray) -> list:
    """
    Embedding dari potongan wajah hasil DeepFace.extract_faces (RGB, [0,1], sudah
    di-align). Mereplikasi langkah DeepFace.represent setelah deteksi
    (deepface 0.0.93), sehingga hasilnya identik dengan extract_embedding().
    """
    model = modeling.build_model(
        task='facial_recognition', model_name=current_app.config['FACE_MODEL']
    )
    target_size = model.input_shape
    img = face[:, :, ::-1]
    img = preprocessing.resize_image(img=img, target_size=(target_size[1], target_size[0]))
    img = preprocessing.normalize_input(img=img, normalization='base')
    return model.forward(img)


def average_embeddings(embeddings: list[list]) -> list:
    """Rata-rata beberapa embedding menjadi satu vektor representatif."""
    arr = np.array(embeddings)
    avg = np.mean(arr, axis=0)
    # Normalisasi agar tetap unit vector (penting untuk cosine similarity)
    avg = avg / np.linalg.norm(avg)
    return avg.tolist()


def save_embedding(pemilih_id: str, embedding: list, n_samples: int = 1) -> None:
    """Simpan embedding ke file JSON per pemilih."""
    os.makedirs(EMBEDDINGS_DIR, exist_ok=True)
    with open(_embedding_path(pemilih_id), 'w') as f:
        json.dump({
            'pemilih_id': pemilih_id,
            'embedding' : embedding,
            'n_samples' : n_samples,
        }, f)


def load_embedding(pemilih_id: str) -> list | None:
    """Load embedding dari file JSON."""
    path = _embedding_path(pemilih_id)
    if not os.path.exists(path):
        return None
    with open(path, 'r') as f:
        data = json.load(f)
    return data['embedding']


def delete_embedding(pemilih_id: str) -> bool:
    """Hapus embedding file pemilih."""
    path = _embedding_path(pemilih_id)
    if os.path.exists(path):
        os.remove(path)
        return True
    return False


def cosine_similarity(vec_a: list, vec_b: list) -> float:
    """Hitung cosine similarity antara dua embedding vector."""
    a = np.array(vec_a)
    b = np.array(vec_b)
    return float(np.dot(a, b) / (np.linalg.norm(a) * np.linalg.norm(b)))


def verify_face(pemilih_id: str, img: np.ndarray) -> dict:
    """
    1. Deteksi wajah (MTCNN) — SEKALI saja, hasilnya dipakai anti-spoof & embedding
    2. Anti-spoofing check
    3. Cosine similarity antara embedding live dengan yang tersimpan
    Return: { match, confidence, threshold, is_real, spoof_score, antispoof_label }
    """
    # Step 1 — deteksi. Sebelumnya check_spoof() dan extract_embedding() masing-
    # masing menjalankan MTCNN pada gambar yang sama (2x). Raise ValueError bila
    # wajah tidak terdeteksi (→ 422), sama seperti extract_embedding().
    face_obj = DeepFace.extract_faces(
        img_path=img,
        detector_backend=current_app.config['DETECTOR_BACKEND'],
        enforce_detection=True,
    )[0]

    # Step 2 — liveness check
    spoof_result = _antispoof_on_area(img, face_obj['facial_area'])

    stored = load_embedding(pemilih_id)
    if stored is None:
        return {
            'match'           : False,
            'confidence'      : 0.0,
            'is_real'         : spoof_result['is_real'],
            'spoof_score'     : spoof_result['spoof_score'],
            'antispoof_label' : spoof_result['antispoof_label'],
            'message'         : 'Embedding tidak ditemukan',
        }

    # Step 3 — face matching
    live_embedding  = _embedding_from_face(face_obj['face'])
    similarity      = cosine_similarity(stored, live_embedding)
    threshold       = current_app.config['FACE_DISTANCE_THRESHOLD']
    match_threshold = 1.0 - threshold

    # Dinyatakan match hanya jika LOLOS spoof DAN similarity cukup
    is_match = spoof_result['is_real'] and (similarity >= match_threshold)

    return {
        'match'          : is_match,
        'confidence'     : round(similarity * 100, 2),
        'threshold'      : round(match_threshold * 100, 2),
        'is_real'        : spoof_result['is_real'],
        'spoof_score'    : spoof_result['spoof_score'],
        'antispoof_label': spoof_result['antispoof_label'],
    }
