# Face Recognition API — AI Context

## Apa ini?
Microservice Python (Flask) untuk verifikasi identitas wajah.
Digunakan oleh sistem **E-Vote Mahasiswa** (Laravel, `/Applications/MAMP/htdocs/e_vote`) sebagai layer
verifikasi biometrik saat registrasi dan voting mahasiswa.

Bagian dari penelitian:
> *Sistem E-Voting Akademik dengan Verifikasi Identitas Mahasiswa Menggunakan Face Recognition Berbasis Deep Learning*

---

## Stack & Versi Penting
| Komponen | Versi | Catatan |
|---|---|---|
| Python | 3.12 | TensorFlow belum support 3.13+ |
| Flask | 3.1.1 | |
| DeepFace | 0.0.93 | Wrapper multi-model face recognition |
| TensorFlow | 2.19.1 | Backend DeepFace |
| Model aktif | Facenet512 | Ganti di `.env` jika perlu benchmarking |
| Detector | mtcnn | Deteksi & alignment wajah akurat untuk variasi pose (frontal/kiri/kanan) |
| Port | 5055 | 5001 & 5050 dipakai OrbStack |

---

## Struktur Project
```
face-recognition-api/
├── app/
│   ├── __init__.py              # create_app(), CORS, config dari .env
│   ├── routes/
│   │   ├── face.py              # /api/face/* — endpoint utama
│   │   └── health.py            # /api/health
│   ├── services/
│   │   └── face_service.py      # Logic: extract, save, load, verify embedding
│   └── utils/
│       ├── image.py             # base64 ↔ numpy array
│       └── auth.py              # @require_api_key decorator
├── storage/
│   └── embeddings/              # {pemilih_id}.json — satu file per pemilih
├── docs/
│   ├── API.md                   # Dokumentasi endpoint lengkap
│   ├── TODO.md                  # Roadmap & backlog
│   └── INTEGRATION.md           # Cara integrasi dengan Laravel e_vote
├── tests/                       # Unit & integration tests (belum ada, lihat TODO)
├── venv/                        # Python 3.12 virtualenv
├── .env                         # Config lokal (tidak di-commit)
├── .env.example                 # Template config
├── requirements.txt
├── run.py                       # Entry point development
└── CLAUDE.md                    # File ini
```

---

## Cara Kerja Sistem (Alur Data)

### Registrasi Wajah
```
Browser (kamera) → base64 image
    → POST /api/face/register {pemilih_id, image_base64}
    → DeepFace.represent() → embedding vector (512 dimensi untuk Facenet512)
    → Disimpan ke storage/embeddings/{pemilih_id}.json
    → Response: {success, message}
```

### Verifikasi Wajah (Login / Voting)
```
Browser (kamera) → base64 image
    → POST /api/face/verify {pemilih_id, image_base64}
    → Load embedding dari JSON
    → DeepFace.represent() pada gambar baru → embedding baru
    → Cosine similarity antara dua embedding
    → Jika similarity >= (1 - threshold): match=true
    → Response: {match, confidence, threshold}
```

### Formula Cosine Similarity
```
similarity = dot(a, b) / (norm(a) * norm(b))
match = similarity >= (1.0 - FACE_DISTANCE_THRESHOLD)
```
Contoh: threshold=0.40 → match jika similarity >= 0.60 (60%)

---

## Environment Variables (.env)
| Variable | Default | Keterangan |
|---|---|---|
| `FLASK_ENV` | `development` | `production` untuk deploy |
| `FLASK_PORT` | `5055` | Port server |
| `API_SECRET_KEY` | — | Wajib diisi, dikirim via header `X-API-Key` |
| `FACE_MODEL` | `Facenet512` | Opsi: `VGG-Face`, `Facenet`, `ArcFace`, `DeepFace` |
| `DETECTOR_BACKEND` | `mtcnn` | Opsi: `opencv`, `ssd`, `mtcnn`, `retinaface` |
| `FACE_DISTANCE_THRESHOLD` | `0.40` | Makin kecil = makin ketat |

---

## Aturan Pengembangan

### Yang BOLEH diubah tanpa risiko
- Threshold di `.env` — tidak perlu re-registrasi wajah
- Menambah endpoint baru di `app/routes/`
- Menambah utilitas di `app/utils/`

### Yang HARUS dipikirkan matang sebelum diubah
- **Model** (`FACE_MODEL`) — ganti model = semua embedding lama tidak valid, harus re-registrasi semua pemilih
- **Format penyimpanan embedding** (`face_service.py`) — jika ubah struktur JSON, buat migrasi
- **Formula similarity** — perubahan kecil bisa ubah siapa yang lolos/gagal verifikasi

### Konvensi Kode
- Semua endpoint return JSON dengan field `success: bool` dan `message: str`
- Error client (input salah): HTTP 422
- Error auth: HTTP 401
- Error server: HTTP 500
- Image selalu diterima dalam format base64, boleh dengan atau tanpa prefix `data:image/...;base64,`

### Keamanan
- Semua endpoint `/api/face/*` dilindungi `@require_api_key`
- API key dikirim via header `X-API-Key` (bukan query param)
- File embedding di `storage/embeddings/` tidak boleh di-commit (lihat `.gitignore`)
- Untuk production: ganti penyimpanan embedding ke database (lihat TODO fase 3)

---

## Perintah Penting
```bash
# Aktifkan venv
source venv/bin/activate

# Jalankan server development
python run.py

# Test health
curl http://127.0.0.1:5055/api/health

# Install dependency baru
pip install <package> && pip freeze > requirements.txt
```

---

## Integrasi dengan Laravel (e_vote)
Lihat `docs/INTEGRATION.md` untuk detail lengkap.
Ringkasan: Laravel memanggil API ini via HTTP menggunakan `Http::withHeaders(['X-API-Key' => ...])`.
Config di Laravel: `FACE_API_URL` dan `FACE_API_KEY` di file `.env` e_vote.

---

## Konteks Penelitian
- **Pemilih** = mahasiswa dengan data NIM/no_anggota
- **Verifikasi** dilakukan 2 kali: saat login (opsional) dan saat submit vote (wajib)
- **Akurasi** menjadi bagian dari pengujian penelitian — catat nilai confidence setiap verifikasi
- **Dataset** untuk pengujian: foto dari proses registrasi pengguna nyata
- Pertimbangkan **liveness detection** sebagai pengembangan lanjutan (anti-foto/spoofing)
