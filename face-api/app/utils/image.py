import base64
import numpy as np
import cv2
from PIL import Image
import io
import os
import re

# Batas sisi terpanjang gambar sebelum deteksi wajah. MTCNN membangun image
# pyramid yang biayanya sebanding jumlah piksel — foto ponsel (jutaan piksel)
# bisa memakan puluhan detik. Foto webcam login pun sering 720p/1080p.
# Menurunkan ke <=640px memangkas biaya deteksi berkali lipat tanpa menurunkan
# akurasi berarti (Facenet512 tetap menormalkan crop wajah ke 160x160).
# Foto registrasi dari browser (300x300) berada di bawah batas → tidak berubah.
MAX_IMAGE_DIM = int(os.getenv('FACE_MAX_IMAGE_DIM', '640'))


def downscale(img: np.ndarray, max_dim: int = MAX_IMAGE_DIM) -> np.ndarray:
    """Kecilkan gambar bila sisi terpanjang melebihi max_dim (jaga rasio aspek)."""
    if max_dim and max_dim > 0:
        h, w = img.shape[:2]
        longest = max(h, w)
        if longest > max_dim:
            scale = max_dim / float(longest)
            img = cv2.resize(img, (max(1, int(w * scale)), max(1, int(h * scale))),
                             interpolation=cv2.INTER_AREA)
    return img


def base64_to_numpy(b64_string: str) -> np.ndarray:
    """Konversi base64 image string ke numpy array (BGR untuk OpenCV), di-downscale."""
    # Hapus prefix data:image/...;base64, jika ada
    b64_string = re.sub(r'^data:image/[^;]+;base64,', '', b64_string)
    img_bytes = base64.b64decode(b64_string)
    img = Image.open(io.BytesIO(img_bytes)).convert('RGB')
    arr = cv2.cvtColor(np.array(img), cv2.COLOR_RGB2BGR)
    return downscale(arr)

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
