import base64
import numpy as np
import cv2
from PIL import Image
import io
import re

def base64_to_numpy(b64_string: str) -> np.ndarray:
    """Konversi base64 image string ke numpy array (BGR untuk OpenCV)."""
    # Hapus prefix data:image/...;base64, jika ada
    b64_string = re.sub(r'^data:image/[^;]+;base64,', '', b64_string)
    img_bytes = base64.b64decode(b64_string)
    img = Image.open(io.BytesIO(img_bytes)).convert('RGB')
    return cv2.cvtColor(np.array(img), cv2.COLOR_RGB2BGR)

def numpy_to_base64(img: np.ndarray) -> str:
    """Konversi numpy array ke base64 string."""
    _, buffer = cv2.imencode('.jpg', img)
    return base64.b64encode(buffer).decode('utf-8')

def validate_base64_image(b64_string: str) -> bool:
    try:
        img = base64_to_numpy(b64_string)
        return img is not None and img.size > 0
    except Exception:
        return False
