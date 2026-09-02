# Protokol Pengujian Akurasi Face Recognition — E-Voting Akademik

Protokol ini menghasilkan data kredibel untuk mengisi **Tabel 3** (metrik), **Gambar 7**
(ROC), dan **pengganti Gambar 8** (distribusi similarity) pada artikel, sekaligus menjaga
kesesuaian dengan sistem nyata (Facenet512 + MTCNN + cosine similarity).

---

## 1. Tujuan

Mengukur seberapa akurat sistem memverifikasi identitas (1:1) — yakni membedakan
**genuine** (wajah pemilih sah) dari **impostor** (wajah orang lain) — pada berbagai
kondisi pencahayaan, pose, dan aksesori.

---

## 2. Subjek & ukuran sampel

| Parameter | Rekomendasi | Minimum layak |
|---|---|---|
| Jumlah subjek (mahasiswa) | **50** (sesuai narasi artikel) | 30 |
| Foto enrollment / subjek | **5** (depan, kiri, kanan, menunduk, mendongak) | 5 |
| Foto uji / subjek | **8–10** (sesi berbeda dari enrollment) | 6 |

- **Informed consent** tertulis dari tiap subjek (wajib untuk etika & Sinta 2).
- Foto uji **harus diambil di sesi/hari berbeda** dari enrollment agar valid (bukan
  frame dari video yang sama).

### Variasi kondisi foto uji (bagi rata)
- **Pencahayaan:** terang, redup, cahaya samping.
- **Pose:** frontal, sedikit menoleh kiri/kanan, menunduk/mendongak.
- **Aksesori:** sebagian dengan kacamata / tanpa kacamata (opsional: masker untuk uji
  kasus sulit — dilaporkan terpisah).

Contoh: 50 subjek × 8 foto uji = **400 foto uji**.

---

## 3. Definisi pasangan uji

- **Pasangan genuine:** foto uji subjek X dibandingkan dengan template subjek X.
  → jumlah = total foto uji (mis. 400).
- **Pasangan impostor:** foto uji subjek X dibandingkan dengan template subjek lain (≠X).
  → jumlah = foto uji × (subjek − 1), mis. 400 × 49 = **19.600**.

Setiap perbandingan menghasilkan satu skor **cosine similarity** (0–1).

---

## 4. Metrik & rumus

Pada sebuah **threshold** t (operasional sistem = **0,60**), sebuah perbandingan
**diterima** bila similarity ≥ t.

| Simbol | Arti |
|---|---|
| TP | genuine diterima (benar) |
| FN | genuine ditolak (salah) |
| TN | impostor ditolak (benar) |
| FP | impostor diterima (salah) |

```
Akurasi   = (TP + TN) / (TP + TN + FP + FN)
Presisi   = TP / (TP + FP)
Recall    = TP / (TP + FN)              (= TAR = True Accept Rate = 1 − FRR)
F1-Score  = 2 · Presisi · Recall / (Presisi + Recall)

FAR (False Acceptance Rate) = FP / (FP + TN)   → impostor lolos (risiko keamanan)
FRR (False Rejection Rate)  = FN / (FN + TP)   → pemilih sah ditolak (risiko kenyamanan)
```

**Metrik ambang-independen (kuat untuk publikasi):**
- **ROC** = kurva TAR terhadap FAR untuk semua threshold; **AUC** = luas di bawahnya
  (makin dekat 1 makin baik).
- **EER (Equal Error Rate)** = titik saat FAR = FRR (makin kecil makin baik).

> **Catatan:** akurasi bergantung pada threshold, jadi **selalu laporkan juga EER & AUC**
> — reviewer Sinta 2 menghargai metrik yang tidak bias terhadap pemilihan threshold.

---

## 5. Prosedur

1. **Kumpulkan data** dari tiap subjek (5 enroll + ≥6 uji) sesuai variasi kondisi.
2. **Susun folder** persis seperti struktur di bawah.
3. **Jalankan script evaluasi** (`evaluate_accuracy.py`).
4. **Salin hasil** ke Tabel 3 / Gambar 7 / Gambar 8 artikel.

### Struktur folder dataset
```
eval_dataset/
├── subjek_01/
│   ├── enroll/   depan.jpg  kiri.jpg  kanan.jpg  menunduk.jpg  mendongak.jpg
│   └── test/     uji1.jpg  uji2.jpg  ...  uji8.jpg
├── subjek_02/
│   ├── enroll/   ...
│   └── test/     ...
└── ...
```

### Menjalankan
```bash
cd face-api
# sekali saja: pasang dependensi grafik
pip install -r scripts/requirements-eval.txt
# jalankan
python scripts/evaluate_accuracy.py --dataset ./eval_dataset --threshold 0.60 --out ./eval_output
```
Script memakai **model & detektor yang sama dengan sistem** (Facenet512 + MTCNN), jadi
hasilnya sahih mewakili sistem produksi.

---

## 6. Pemetaan output → artikel

| Output script | Dipakai untuk |
|---|---|
| ringkasan di layar + `metrics.csv` | **Tabel 3** (Akurasi, Presisi, Recall, F1) + tambahkan FAR/FRR/EER/AUC |
| `roc_curve.png` | **Gambar 7** (Kurva ROC) |
| `similarity_distribution.png` | **pengganti Gambar 8** (distribusi genuine vs impostor) |
| `threshold_sweep.csv` | lampiran / justifikasi pemilihan threshold 0,60 |

---

## 7. Pengujian anti-spoofing (Presentation Attack Detection / PAD)

Membuktikan klaim "menolak foto cetak & video replay" secara terukur, memakai metrik
standar ISO/IEC 30107-3.

### Metrik
```
APCER = proporsi SERANGAN yang keliru dianggap asli   (makin kecil makin baik)
BPCER = proporsi WAJAH ASLI yang keliru dianggap spoof (makin kecil makin baik)
ACER  = (APCER + BPCER) / 2
Attack Detection Rate = 1 - APCER   (persentase serangan yang ditolak)
```

### Data
- **Bonafide:** foto/live wajah asli subjek (bisa pakai foto uji dari bagian 2).
- **Serangan:** untuk sebagian subjek, hadirkan ke kamera:
  - **print** — foto wajah subjek yang dicetak, lalu difoto ulang;
  - **replay** — video/foto wajah subjek ditampilkan di layar HP/laptop, lalu difoto.
- Rekomendasi: ≥ 50 bonafide + ≥ 50 serangan (campur print & replay).

### Struktur folder
```
pad_dataset/
├── bonafide/        a1.jpg a2.jpg ...
└── attack/
    ├── print/       p1.jpg ...
    └── replay/      r1.jpg ...
```

### Menjalankan
```bash
cd face-api
python scripts/evaluate_antispoof.py --dataset ./pad_dataset --out ./pad_output
```
Output `pad_metrics.json` memuat APCER, BPCER, ACER, dan detection rate per tipe serangan
— siap dikutip di bagian Pembahasan keamanan.

## 8. Pengujian pendukung lain

1. **Waktu respons**: catat rata-rata durasi verifikasi (target artikel < 3 detik) pada VPS.
2. **Robustness per kondisi**: pecah akurasi per kelompok (terang vs redup, dengan vs tanpa
   kacamata) untuk analisis pembahasan.

---

## 9. Etika data

- Simpan foto subjek terenkripsi/terbatas akses; hapus setelah penelitian selesai bila
  disepakati di consent.
- Jangan commit `eval_dataset/` maupun `eval_output/` yang memuat wajah ke Git.
