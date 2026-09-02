"""
Konfigurasi gunicorn untuk Face API.

Kenapa gunicorn (bukan `python run.py`):
- `app.run()` = Werkzeug dev server, single-thread → hanya melayani 1 koneksi
  pada satu waktu. Saat registrasi serentak (mis. 40 mahasiswa), koneksi
  berlebih ditolak/di-reset → cURL error 52/55/56 di sisi Laravel.
- gunicorn menampung kelebihan koneksi di antrean (backlog) lalu memprosesnya
  oleh sejumlah worker → tidak ada lagi koneksi diputus paksa.

Sizing untuk server 2 core / RAM ~7.5GB (dipakai bersama layanan lain):
- workers = 2  → cocok dengan 2 core (inference TensorFlow itu CPU-bound).
- Tiap worker memuat model sendiri (~1.3GB). 2 worker ~2.6GB, masih aman.
- timeout longgar karena 1 request memproses beberapa foto (deteksi + embedding).
"""
import os

bind = f"0.0.0.0:{os.getenv('FLASK_PORT', '5055')}"

# 2 worker = 2 core. Bisa dinaikkan lewat env bila server diupgrade.
workers = int(os.getenv('GUNICORN_WORKERS', '2'))
worker_class = 'sync'

# Registrasi memproses beberapa foto (MTCNN + Facenet512) → butuh waktu.
timeout = int(os.getenv('GUNICORN_TIMEOUT', '120'))
graceful_timeout = 120

# Antrean koneksi saat burst — inilah yang mencegah "connection reset".
backlog = int(os.getenv('GUNICORN_BACKLOG', '256'))

# Daur ulang worker sesekali untuk mencegah kebocoran memori TensorFlow.
max_requests = 200
max_requests_jitter = 50

# Muat ulang model per worker (lihat create_app → warmup). Tidak pakai
# --preload agar tiap worker punya salinan model sendiri (lebih aman untuk
# TensorFlow terhadap fork).
preload_app = False

accesslog = '-'
errorlog = '-'
loglevel = os.getenv('GUNICORN_LOGLEVEL', 'info')
