from flask import Flask
from flask_cors import CORS
from dotenv import load_dotenv
import os

load_dotenv()


def _str2bool(val: str) -> bool:
    return str(val).strip().lower() in ('1', 'true', 'yes', 'y', 'on')


def _warmup(model: str, detector: str):
    """
    Muat model DeepFace (detector + recognition) sekali saat worker start,
    supaya request pertama tidak sangat lambat dan tidak terjadi "stampede"
    pemuatan model saat banyak request masuk bersamaan (burst registrasi).
    Non-fatal: kalau gagal, worker tetap jalan dan model dimuat saat request pertama.
    """
    try:
        import numpy as np
        from deepface import DeepFace
        dummy = np.zeros((160, 160, 3), dtype=np.uint8)
        DeepFace.represent(
            img_path=dummy,
            model_name=model,
            detector_backend=detector,
            enforce_detection=False,   # gambar kosong: cukup untuk memuat model
        )
        print(f'[warmup] model {model} + detector {detector} siap.', flush=True)
    except Exception as e:
        print(f'[warmup] dilewati ({e}) — model akan dimuat saat request pertama.', flush=True)


def create_app():
    app = Flask(__name__)
    CORS(app, resources={r"/api/*": {"origins": "*"}})

    app.config['API_SECRET_KEY'] = os.getenv('API_SECRET_KEY', 'secret')
    app.config['FACE_MODEL'] = os.getenv('FACE_MODEL', 'Facenet512')
    app.config['DETECTOR_BACKEND'] = os.getenv('DETECTOR_BACKEND', 'mtcnn')
    app.config['FACE_DISTANCE_THRESHOLD'] = float(os.getenv('FACE_DISTANCE_THRESHOLD', 0.40))

    # Anti-spoof saat REGISTRASI dimatikan secara default: mahasiswa mendaftarkan
    # wajahnya sendiri, dan menjalankan anti-spoof di sini menggandakan beban
    # (deteksi MTCNN jalan 2x + model anti-spoof) → memicu overload saat burst.
    # Anti-spoof tetap AKTIF saat VERIFIKASI/voting (lihat routes/face.py verify).
    app.config['ANTISPOOF_ON_REGISTER'] = _str2bool(os.getenv('ANTISPOOF_ON_REGISTER', 'false'))

    from app.routes.face import face_bp
    app.register_blueprint(face_bp, url_prefix='/api/face')

    from app.routes.health import health_bp
    app.register_blueprint(health_bp, url_prefix='/api')

    # Warmup model per worker (bisa dimatikan via FACE_WARMUP=0 untuk dev lokal).
    if _str2bool(os.getenv('FACE_WARMUP', 'true')):
        _warmup(app.config['FACE_MODEL'], app.config['DETECTOR_BACKEND'])

    return app
