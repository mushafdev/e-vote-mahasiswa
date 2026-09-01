from flask import Flask
from flask_cors import CORS
from dotenv import load_dotenv
import os

load_dotenv()

def create_app():
    app = Flask(__name__)
    CORS(app, resources={r"/api/*": {"origins": "*"}})

    app.config['API_SECRET_KEY'] = os.getenv('API_SECRET_KEY', 'secret')
    app.config['FACE_MODEL'] = os.getenv('FACE_MODEL', 'Facenet512')
    app.config['DETECTOR_BACKEND'] = os.getenv('DETECTOR_BACKEND', 'mtcnn')
    app.config['FACE_DISTANCE_THRESHOLD'] = float(os.getenv('FACE_DISTANCE_THRESHOLD', 0.40))

    from app.routes.face import face_bp
    app.register_blueprint(face_bp, url_prefix='/api/face')

    from app.routes.health import health_bp
    app.register_blueprint(health_bp, url_prefix='/api')

    return app
