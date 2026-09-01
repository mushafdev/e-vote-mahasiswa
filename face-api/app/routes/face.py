from flask import Blueprint, request, jsonify, current_app
from app.utils.auth import require_api_key
from app.utils.image import base64_to_numpy, validate_base64_image
from app.services import face_service

face_bp = Blueprint('face', __name__)


@face_bp.route('/register', methods=['POST'])
@require_api_key
def register():
    """
    Registrasi wajah pemilih — beberapa foto (mis. 5: depan/kiri/kanan/menunduk/
    mendongak), anti-spoofing tiap foto, average embedding disimpan.
    Body JSON: { pemilih_id, images_base64: [b64_1, ...] }
    Backward-compat: image_base64 (singular) juga diterima sebagai 1 foto.
    """
    data       = request.get_json()
    pemilih_id = str(data.get('pemilih_id', '')).strip()

    # Support array maupun singular
    images_b64 = data.get('images_base64') or []
    if not images_b64 and data.get('image_base64'):
        images_b64 = [data['image_base64']]

    if not pemilih_id:
        return jsonify({'success': False, 'message': 'pemilih_id wajib diisi'}), 422
    if not images_b64:
        return jsonify({'success': False, 'message': 'Minimal 1 foto diperlukan'}), 422

    embeddings   = []
    spoof_scores = []
    errors       = []

    for i, b64 in enumerate(images_b64, 1):
        if not validate_base64_image(b64):
            errors.append(f'Foto {i}: format tidak valid')
            continue
        try:
            img          = base64_to_numpy(b64)
            spoof        = face_service.check_spoof(img)
            spoof_scores.append(spoof)

            if not spoof['is_real']:
                errors.append(f'Foto {i}: terdeteksi sebagai foto/video (spoof)')
                continue

            emb = face_service.extract_embedding(img)
            embeddings.append(emb)
        except ValueError as e:
            errors.append(f'Foto {i}: wajah tidak terdeteksi')
        except Exception as e:
            current_app.logger.error(f'[register] foto {i}: {e}')
            errors.append(f'Foto {i}: gagal diproses')

    if not embeddings:
        return jsonify({
            'success': False,
            'message': 'Tidak ada foto valid yang dapat diproses',
            'errors' : errors,
        }), 422

    final_embedding = (
        face_service.average_embeddings(embeddings)
        if len(embeddings) > 1
        else embeddings[0]
    )
    face_service.save_embedding(pemilih_id, final_embedding, n_samples=len(embeddings))

    return jsonify({
        'success'      : True,
        'message'      : f'Wajah berhasil didaftarkan ({len(embeddings)} foto valid)',
        'pemilih_id'   : pemilih_id,
        'n_samples'    : len(embeddings),
        'spoof_scores' : spoof_scores,
        'warnings'     : errors if errors else None,
    })


@face_bp.route('/verify', methods=['POST'])
@require_api_key
def verify():
    """
    Verifikasi wajah saat voting.
    Body JSON: { pemilih_id, image_base64 }
    Response tambahan: is_real, spoof_score, antispoof_label
    """
    data       = request.get_json()
    pemilih_id = str(data.get('pemilih_id', '')).strip()
    image_b64  = data.get('image_base64', '')

    if not pemilih_id:
        return jsonify({'success': False, 'message': 'pemilih_id wajib diisi'}), 422
    if not image_b64 or not validate_base64_image(image_b64):
        return jsonify({'success': False, 'message': 'Format gambar tidak valid'}), 422

    try:
        img    = base64_to_numpy(image_b64)
        result = face_service.verify_face(pemilih_id, img)

        if not result['is_real']:
            msg = 'Terdeteksi foto/video — gunakan wajah asli'
        elif result['match']:
            msg = 'Wajah cocok'
        else:
            msg = 'Wajah tidak cocok'

        return jsonify({
            'success'        : True,
            'match'          : result['match'],
            'confidence'     : result['confidence'],
            'threshold'      : result.get('threshold'),
            'is_real'        : result['is_real'],
            'spoof_score'    : result['spoof_score'],
            'antispoof_label': result['antispoof_label'],
            'message'        : msg,
        })
    except ValueError as e:
        return jsonify({'success': False, 'message': f'Wajah tidak terdeteksi: {str(e)}'}), 422
    except Exception as e:
        current_app.logger.error(f'[verify] {e}')
        return jsonify({'success': False, 'message': 'Terjadi kesalahan server'}), 500


@face_bp.route('/delete/<pemilih_id>', methods=['DELETE'])
@require_api_key
def delete(pemilih_id):
    """Hapus embedding wajah pemilih (misal saat akun dihapus)."""
    deleted = face_service.delete_embedding(str(pemilih_id))
    return jsonify({
        'success': deleted,
        'message': 'Embedding dihapus' if deleted else 'Embedding tidak ditemukan',
    })


@face_bp.route('/status/<pemilih_id>', methods=['GET'])
@require_api_key
def status(pemilih_id):
    """Cek apakah pemilih sudah punya embedding terdaftar."""
    import os
    path = face_service._embedding_path(str(pemilih_id))
    registered = os.path.exists(path)
    return jsonify({
        'success': True,
        'pemilih_id': pemilih_id,
        'registered': registered,
    })
