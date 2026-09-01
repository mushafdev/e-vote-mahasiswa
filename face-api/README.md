# Face Recognition API

Microservice Python untuk verifikasi identitas wajah berbasis Deep Learning.
Bagian dari sistem **E-Voting Akademik** — penelitian S1/S2.

> Sistem E-Voting Akademik dengan Verifikasi Identitas Mahasiswa  
> Menggunakan Face Recognition Berbasis Deep Learning

---

## Quick Start

```bash
# 1. Clone / masuk folder
cd face-recognition-api

# 2. Aktifkan virtual environment (Python 3.12)
source venv/bin/activate

# 3. Copy dan sesuaikan config
cp .env.example .env

# 4. Jalankan server
python run.py
```

Server berjalan di **http://127.0.0.1:5055**

```bash
# Cek status
curl http://127.0.0.1:5055/api/health
```

---

## Dokumentasi

| Dokumen | Isi |
|---|---|
| [CLAUDE.md](./CLAUDE.md) | Konteks AI — arsitektur, aturan, cara kerja sistem |
| [docs/API.md](./docs/API.md) | Referensi lengkap semua endpoint |
| [docs/TODO.md](./docs/TODO.md) | Roadmap & backlog pengembangan |
| [docs/INTEGRATION.md](./docs/INTEGRATION.md) | Panduan integrasi dengan Laravel e_vote |

---

## Teknologi

- **Python 3.12** + **Flask 3.1**
- **DeepFace** — wrapper multi-model face recognition
- **TensorFlow 2.19** — backend deep learning
- **Model:** Facenet512 (akurasi 99.65% di benchmark LFW)
- **Detector:** OpenCV (cepat, cocok untuk kondisi lab)

---

## Endpoint Ringkas

| Method | URL | Fungsi |
|---|---|---|
| GET | `/api/health` | Status server |
| POST | `/api/face/register` | Daftarkan wajah |
| POST | `/api/face/verify` | Verifikasi wajah |
| GET | `/api/face/status/{id}` | Cek status registrasi |
| DELETE | `/api/face/delete/{id}` | Hapus data wajah |

Semua endpoint `/api/face/*` butuh header `X-API-Key`.
