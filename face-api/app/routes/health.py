from flask import Blueprint, jsonify
import os

health_bp = Blueprint('health', __name__)

@health_bp.route('/health', methods=['GET'])
def health():
    return jsonify({
        'status': 'ok',
        'model': os.getenv('FACE_MODEL', 'Facenet512'),
        'detector': os.getenv('DETECTOR_BACKEND', 'opencv'),
    })
