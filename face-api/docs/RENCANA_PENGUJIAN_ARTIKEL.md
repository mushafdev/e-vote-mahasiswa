# Rencana Pengujian — Pemetaan ke Artikel

Dokumen ini mengaitkan **setiap placeholder `[HASIL PENGUJIAN NYATA]`** di artikel ke
uji konkret, output, dan lokasi di artikel. Untuk detail akurasi & anti-spoofing lihat
[PROTOKOL_PENGUJIAN_AKURASI.md](PROTOKOL_PENGUJIAN_AKURASI.md) (tidak diduplikasi di sini).

Ada **5 aktivitas uji**:

| Kode | Aktivitas | Mengisi bagian artikel |
|---|---|---|
| A | Akurasi model (genuine vs impostor) | Tabel 3, Gambar 7, Gambar 8, detection rate (3.1) |
| B | Anti-spoofing (PAD) | Klaim keamanan 3.3 & 3.6 |
| C | Black-box fungsional | Tabel 2, Tabel 5, tingkat keberhasilan |
| D | Usability (SUS) | Tabel 6, Gambar 11, skor SUS |
| E | Waktu respons & dataset | "waktu respons < 3 dtk" (3.3), distribusi pose (Gambar 6) |

---

## 0. Prasyarat (lakukan sekali sebelum mulai)

- [ ] **Deploy final** Face API (gunicorn + downscaling) & Laravel (rollback + gate login).
- [ ] **Informed consent** tertulis tiap subjek (wajib etika + Sinta 2). Simpan arsipnya.
- [ ] Pastikan **anti-spoofing aktif saat verifikasi** (`ANTISPOOF_ON_REGISTER=false` → registrasi
      cepat; liveness tetap jalan di login & voting). **Selaraskan kalimat artikel** (lihat §7).
- [ ] Siapkan `pip install -r scripts/requirements-eval.txt` untuk grafik.

---

## 1. Peta induk placeholder → sumber data

| Placeholder di artikel | Aktivitas | Output/sumber |
|---|---|---|
| Akurasi, Presisi, Recall, F1 (Abstract, Tabel 3, Kesimpulan) | A | `metrics.csv` |
| AUC + kurva ROC (Gambar 7) | A | `roc_curve.png` |
| Distribusi genuine vs impostor (Gambar 8) | A | `similarity_distribution.png` |
| Justifikasi ambang 0,60 | A | `threshold_sweep.csv` |
| Jumlah subjek, total citra uji (2.2, 3.1) | A/E | rekap dataset |
| Detection rate MTCNN, citra dieksklusi (3.1, 3.6) | A | ringkasan `evaluate_accuracy.py` (baris `[SKIP]`) |
| Distribusi 5 pose (Gambar 6) | E | rekap dataset → bar chart |
| Tolak foto cetak & video replay (3.3, 3.6) | B | `pad_metrics.json` (APCER/BPCER/ACER) |
| Jumlah skenario black-box + hasil (Tabel 2, Tabel 5, keberhasilan) | C | tabel uji manual |
| Jumlah responden, skor SUS, kategori (Tabel 6, Gambar 11) | D | rekap kuesioner |
| Waktu respons verifikasi rata-rata (3.3) | E | pengukuran verify |

---

## 2. Aktivitas A — Akurasi model

Ikuti [PROTOKOL_PENGUJIAN_AKURASI.md](PROTOKOL_PENGUJIAN_AKURASI.md) §2–6. Ringkas:

1. Kumpulkan **≥30 subjek** (target 50) × 5 foto enroll + ≥6 foto uji **sesi berbeda**, variasi
   pencahayaan/pose/kacamata.
2. Susun folder `eval_dataset/subjek_xx/enroll|test/`.
3. Jalankan **dua kali** — untuk sekaligus membuktikan downscaling tak menurunkan akurasi:
   ```bash
   # meniru produksi (gambar di-downscale ke 640)
   python scripts/evaluate_accuracy.py --dataset ./eval_dataset --threshold 0.60 --max-dim 640 --out ./eval_640
   # resolusi penuh (pembanding)
   python scripts/evaluate_accuracy.py --dataset ./eval_dataset --threshold 0.60 --max-dim 0 --out ./eval_full
   ```
4. Pakai hasil `eval_640` (mewakili produksi) untuk Tabel 3 / Gambar 7 / Gambar 8. Sebutkan di
   Pembahasan bahwa metrik `eval_full` ≈ `eval_640` → downscaling aman.

> **Detection rate** (Gambar 3.1) = (jumlah foto berhasil diproses) / (total foto). Baris `[SKIP]`
> pada output skrip = foto yang wajahnya gagal terdeteksi → dieksklusi.

---

## 3. Aktivitas B — Anti-spoofing (PAD)

Ikuti [PROTOKOL_PENGUJIAN_AKURASI.md](PROTOKOL_PENGUJIAN_AKURASI.md) §7. Siapkan ≥50 bonafide +
≥50 serangan (print + replay), lalu:
```bash
python scripts/evaluate_antispoof.py --dataset ./pad_dataset --out ./pad_output
```
Laporkan **APCER, BPCER, ACER, Attack Detection Rate** di Pembahasan keamanan.

> Karena liveness dijalankan **saat verifikasi (login & voting)**, uji PAD ini mewakili titik
> pertahanan sistem yang sebenarnya.

---

## 4. Aktivitas C — Black-box fungsional (Tabel 2 & 5)

Uji manual tiap skenario, catat **Berhasil/Gagal**. Tingkat keberhasilan = (berhasil/total)×100%.
Skenario di bawah sesuai alur sistem nyata — sesuaikan bila perlu.

| No | Modul | Skenario Uji | Input | Output diharapkan |
|----|-------|--------------|-------|-------------------|
| 1 | Login & Autentikasi | Login kredensial benar | username + password valid | Lanjut ke verifikasi wajah |
| 2 | Login & Autentikasi | Password salah | password salah | Ditolak: "Username atau password tidak valid" |
| 3 | Login & Autentikasi | Akun tanpa embedding wajah | akun belum daftar wajah | Pesan "Wajah belum terdaftar…" (bukan diteruskan) |
| 4 | Login & Autentikasi | Sudah login di perangkat lain | login kedua | Ditolak: "sudah login di perangkat lain" |
| 5 | Registrasi Wajah | Registrasi data lengkap + 5 foto | data valid + 5 foto | Akun dibuat, embedding tersimpan, sukses |
| 6 | Registrasi Wajah | Field wajib kosong | data tidak lengkap | Ditolak dengan pesan validasi |
| 7 | Registrasi Wajah | Email/username/no_anggota duplikat | data duplikat | Ditolak: sudah digunakan |
| 8 | Registrasi Wajah | Tanpa foto wajah | 0 foto | Ditolak: minimal 1 foto |
| 9 | Registrasi Wajah | Face API gagal (server sibuk) | simulasi gagal | Rollback + pesan "server sibuk, ulangi" (tak ada akun tanpa wajah) |
| 10 | Verifikasi Face Recognition | Wajah pemilik asli | wajah live subjek | Lolos (cosine ≥ 0,60) |
| 11 | Verifikasi Face Recognition | Wajah orang lain | wajah subjek lain | Ditolak (tidak cocok) |
| 12 | Verifikasi Face Recognition | Serangan foto cetak | foto dicetak | Ditolak (anti-spoofing) |
| 13 | Verifikasi Face Recognition | Serangan video replay | wajah di layar HP | Ditolak (anti-spoofing) |
| 14 | Pemungutan Suara | Pilih kandidat + verifikasi sukses | pilih + wajah cocok | Suara tersimpan (terenkripsi) |
| 15 | Pemungutan Suara | Verifikasi gagal saat memilih | wajah tidak cocok | Tidak bisa menyimpan suara |
| 16 | Pemungutan Suara | Cek penyimpanan terenkripsi | inspeksi kolom vote_enc | Ciphertext, bukan plaintext kandidat |
| 17 | Pencegahan Double Voting | Memilih dua kali | pemilih yang sudah memilih | Ditolak: sudah memilih |
| 18 | Rekapitulasi | Rekap hasil real-time | buka dashboard admin | Jumlah suara benar (hasil dekripsi) |
| 19 | Rekapitulasi | Grafik perolehan suara | buka halaman hasil | Grafik batang tampil sesuai data |

**Tabel 5 (ringkasan)** — kelompokkan per modul:

| Modul | Jumlah Skenario | Berhasil | Hasil |
|-------|-----------------|----------|-------|
| Login & Autentikasi | 4 | … | … |
| Registrasi Wajah | 5 | … | … |
| Verifikasi Face Recognition | 4 | … | … |
| Pemungutan Suara | 3 | … | … |
| Pencegahan Double Voting | 1 | … | … |
| Rekapitulasi | 2 | … | … |
| **Total** | **19** | **…** | **…% berhasil** |

---

## 5. Aktivitas D — Usability (SUS)

Sebarkan setelah responden mencoba sistem. **Rekomendasi ≥ 30 responden** mahasiswa.
Skala 1 (Sangat Tidak Setuju) – 5 (Sangat Setuju).

| No | Pernyataan |
|----|------------|
| 1 | Saya rasa saya ingin lebih sering menggunakan sistem ini. |
| 2 | Saya menganggap sistem ini terlalu rumit. |
| 3 | Saya rasa sistem ini mudah digunakan. |
| 4 | Saya rasa saya butuh bantuan orang teknis untuk menggunakan sistem ini. |
| 5 | Saya menemukan berbagai fungsi dalam sistem ini terintegrasi dengan baik. |
| 6 | Saya rasa terlalu banyak ketidakkonsistenan dalam sistem ini. |
| 7 | Saya rasa kebanyakan orang akan cepat belajar menggunakan sistem ini. |
| 8 | Saya merasa sistem ini janggal/menyulitkan saat digunakan. |
| 9 | Saya merasa sangat percaya diri menggunakan sistem ini. |
| 10 | Saya perlu belajar banyak hal sebelum bisa menggunakan sistem ini. |

**Cara hitung skor (per responden):**
- Item ganjil (1,3,5,7,9): kontribusi = `skor − 1`
- Item genap (2,4,6,8,10): kontribusi = `5 − skor`
- **Skor SUS = (jumlah kontribusi 10 item) × 2,5** → rentang 0–100
- Skor akhir sistem = rata-rata skor SUS semua responden

**Kategori (Bangor et al.):** ≥ 80,3 = Excellent (A) · 68–80,3 = Good (B) · 51–68 = OK · < 51 = Poor.

Gambar 11 = bar chart rerata tiap item (1–5); Tabel 6 = skor rerata + bobot per item.

---

## 6. Aktivitas E — Waktu respons & dataset

**Waktu respons verifikasi** (target artikel < 3 dtk): ukur latensi 1 verify (1 foto) di server.
Cara cepat, jalankan beberapa kali lalu rata-ratakan (register→verify satu subjek):
```bash
docker exec evote_face_api python3 scripts/loadtest.py --url http://localhost:5055 \
  --key evote_face_secret_2024 --image /app/storage/foto.jpeg \
  --concurrency 1 --total 5 --photos 1 --verify
```
Ambil latensi **VERIFY** (bukan register). Alternatif paling sahih: ekspor `face_verify_logs`
dari sistem saat uji kelas (sudah mencatat confidence + waktu tiap verifikasi nyata).

**Distribusi pose (Gambar 6):** rekap jumlah foto per pose (depan/kiri/kanan/menunduk/mendongak)
dari dataset enrollment → bar/pie chart.

---

## 7. Penyelarasan artikel (WAJIB dicek sebelum submit)

- [ ] **Anti-spoofing:** ganti kalimat "dijalankan pada setiap **registrasi maupun** verifikasi"
      → "dijalankan pada setiap **verifikasi (login dan pemungutan suara)**". (Bagian 3.6 & 2.4.)
      Alasan defensif: liveness pada titik verifikasi adalah pertahanan sebenarnya; enroll foto
      tetap akan gagal saat verifikasi live.
- [ ] Isi semua `[HASIL PENGUJIAN NYATA]` dari output aktivitas A–E.
- [ ] Tambahkan **FAR, FRR, EER, AUC** ke narasi (reviewer Sinta 2 menghargai metrik ambang-independen).
- [ ] Sertakan hasil **kinerja/beban** (throughput ~1,3 foto/dtk, 40 registrasi serentak = ~156 dtk,
      100% sukses) sebagai bukti non-fungsional "skalabilitas/kecepatan".

---

## 8. Urutan eksekusi yang disarankan

1. Deploy final + consent (§0).
2. **Kumpulkan dataset** (paling makan waktu) — enroll 5 pose + uji sesi berbeda + sampel spoof.
3. Jalankan **A** (akurasi) & **B** (PAD) → tabel/gambar metrik.
4. Lakukan **C** (black-box) di sistem live.
5. Uji kelas nyata → responden isi **D** (SUS) + ekspor `face_verify_logs` untuk **E**.
6. Isi placeholder artikel + selaraskan kalimat (§7).
