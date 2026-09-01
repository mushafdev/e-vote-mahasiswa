from flask import request, jsonify, current_app
from functools import wraps

def require_api_key(f):
    """Middleware: validasi API key dari header X-API-Key."""
    @wraps(f)
    def decorated(*args, **kwargs):
        api_key = request.headers.get('X-API-Key')
        if not api_key or api_key != current_app.config['API_SECRET_KEY']:
            return jsonify({'success': False, 'message': 'Unauthorized'}), 401
        return f(*args, **kwargs)
    return decorated
