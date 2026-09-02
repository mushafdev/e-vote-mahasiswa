FROM python:3.12-slim

RUN apt-get update && apt-get install -y \
    libgl1 libglib2.0-0 libsm6 libxext6 libxrender-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

RUN mkdir -p storage/embeddings

EXPOSE 5055

# Produksi: gunicorn (multi-worker, antre koneksi) — bukan dev server 1-thread.
# Konfigurasi lengkap di gunicorn.conf.py.
CMD ["gunicorn", "-c", "gunicorn.conf.py", "run:app"]
