# TODO & Roadmap

Status: `[ ]` belum · `[x]` selesai · `[~]` sebagian

---

## Fase 1 — Foundation (Selesai)
- [x] Setup project Flask dengan struktur modular
- [x] Integrasi DeepFace (Facenet512 + opencv detector)
- [x] Endpoint `POST /api/face/register` — ekstrak & simpan embedding
- [x] Endpoint `POST /api/face/verify` — verifikasi wajah dengan cosine similarity
- [x] Endpoint `GET /api/face/status/{id}` — cek status registrasi
- [x] Endpoint `DELETE /api/face/delete/{id}` — hapus embedding
- [x] Endpoint `GET /api/health` — health check
- [x] Middleware API key authentication (`X-API-Key`)
- [x] CORS support untuk Laravel
- [x] Konfigurasi via `.env`
- [x] Dokumentasi CLAUDE.md, API.md, TODO.md, INTEGRATION.md

---

## Fase 2 — Integrasi Laravel (Prioritas Tinggi)

### Di project ini (Python API)
- [ ] Tambah endpoint `POST /api/face/register-update` — update embedding yang sudah ada
- [ ] Tambah field `face_quality_score` di response register (seberapa jelas wajah terdeteksi)
- [ ] Logging setiap request verify ke file log (untuk data penelitian)
- [ ] Rate limiting — maks 5 request verify per pemilih per menit
- [ ] Validasi ukuran gambar minimal (reject jika terlalu kecil/buram)

### Di project Laravel (e_vote)
- [x] Tambah kolom `face_registered` (boolean) di tabel `pemilihs`
- [x] Tambah kolom `face_registered_at` (timestamp) di tabel `pemilihs`
- [x] Buat `App\Services\FaceRecognitionService` — wrapper HTTP call ke Python API
- [x] Integrasi kamera (WebRTC) di halaman registrasi — kirim face_image ke API
- [x] Integrasi verifikasi wajah di halaman voting sebelum submit
- [x] Tambah `FACE_API_URL` dan `FACE_API_KEY` ke `.env` e_vote
- [ ] Tampilkan confidence score di halaman hasil verifikasi (untuk penelitian)
- [ ] Hapus embedding saat admin hapus pemilih (panggil `FaceRecognitionService::delete`)

---

## Fase 3 — Peningkatan Keamanan & Akurasi

- [ ] **Liveness Detection** — deteksi apakah wajah nyata atau foto (anti-spoofing)
  - Opsi: kedipan mata, gerakan kepala, atau model dedicated
- [ ] **Multi-angle registration** — daftarkan 3-5 foto dari sudut berbeda, simpan rata-rata embedding
- [ ] Enkripsi file embedding di storage (AES-256)
- [ ] Ganti penyimpanan embedding dari file JSON ke MySQL (tabel `face_embeddings`)
- [ ] Tambah `verify_log` table — catat setiap attempt verifikasi (id, confidence, match, timestamp)
- [ ] Benchmark akurasi: uji dengan foto berbeda kondisi (cahaya, sudut, kacamata)
- [ ] Coba model alternatif: `ArcFace` vs `Facenet512` — bandingkan akurasi & kecepatan

---

## Fase 4 — Production Ready

- [ ] Ganti Flask development server ke **Gunicorn** (sudah terinstall)
  ```bash
  gunicorn -w 2 -b 0.0.0.0:5055 "app:create_app()"
  ```
- [ ] Docker container untuk deployment
- [ ] Environment `production` — matikan debug mode, aktifkan proper logging
- [ ] HTTPS (SSL) untuk API endpoint
- [ ] Monitoring: log request time, confidence score rata-rata, fail rate

---

## Fase 5 — Kebutuhan Penelitian

- [ ] **Dashboard statistik** — endpoint `/api/stats` untuk data penelitian:
  - Total pemilih terdaftar
  - Rata-rata confidence score verifikasi
  - Persentase sukses/gagal verifikasi
  - Distribusi confidence score (histogram)
- [ ] Export log verifikasi ke CSV untuk analisis
- [ ] Dokumentasi hasil pengujian akurasi (confusion matrix, FAR, FRR)
- [ ] Perbandingan threshold: uji 0.30, 0.35, 0.40, 0.45 — catat FAR & FRR

> **FAR** = False Acceptance Rate (orang lain diterima)
> **FRR** = False Rejection Rate (pemilik sah ditolak)
> Target penelitian: FAR < 1%, FRR < 5%

---

## Bug & Issues Diketahui

- [ ] Jika wajah tidak terdeteksi, DeepFace melempar exception generik — perlu pesan error yang lebih informatif
- [ ] Belum ada unit test untuk `face_service.py`
- [ ] Belum ada handling untuk gambar terlalu gelap / blur

---

## Ide Pengembangan Lanjutan (Opsional)

- [ ] WebSocket untuk streaming verifikasi real-time (tanpa upload manual)
- [ ] Mobile app Flutter untuk voting dengan face recognition
- [ ] Integrasi dengan sistem akademik universitas (SIAKAD) untuk validasi mahasiswa aktif
