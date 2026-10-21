# Materi Pengujian — E-Voting Face Recognition

Berkas siap-pakai untuk menjalankan pengujian penelitian. Panduan lengkap & pemetaan ke
artikel ada di [../docs/RENCANA_PENGUJIAN_ARTIKEL.md](../docs/RENCANA_PENGUJIAN_ARTIKEL.md);
protokol akurasi & anti-spoofing di [../docs/PROTOKOL_PENGUJIAN_AKURASI.md](../docs/PROTOKOL_PENGUJIAN_AKURASI.md).

## Isi folder ini

| Berkas | Untuk | Mengisi artikel |
|--------|-------|-----------------|
| `informed_consent.md` | Lembar persetujuan tiap subjek (cetak & tandatangani) | Etika (wajib) |
| `form_blackbox.csv` | 33 skenario uji fungsional & keamanan — isi kolom Hasil/Catatan | Tabel 2 & 5 |
| `kuesioner_sus.xlsx` | Kuesioner SUS + skor & kategori otomatis | Tabel 6, Gambar 11 |
| `google_form_sus.gs` | Apps Script: generate Google Form SUS + spreadsheet skor otomatis | Tabel 6, Gambar 11 |

## Folder dataset (lokal, tidak di-commit — memuat wajah)

Sudah dibuat kerangkanya; tinggal isi foto:

```
eval_dataset/subjek_01/enroll/   ← 5 foto bernama: depan.jpg kiri.jpg kanan.jpg menunduk.jpg mendongak.jpg
eval_dataset/subjek_01/test/     ← ≥6 foto uji SESI BERBEDA
  ... (salin folder subjek_01 untuk subjek_02, 03, dst — target ≥30 subjek)

pad_dataset/bonafide/            ← foto wajah asli
pad_dataset/attack/print/        ← foto cetak difoto ulang
pad_dataset/attack/replay/       ← wajah di layar HP/laptop difoto
```

## Cara pakai kuesioner_sus.xlsx

- Sheet **Data**: isi sel **kuning** (P1–P10, skala 1–5) tiap responden. Kolom *Skor SUS* &
  *Kategori* terisi otomatis. Baris **CONTOH** hanya contoh (tidak dihitung).
- Ringkasan (bawah): jumlah responden, **skor SUS rata-rata**, dan kategori akhir.
- Sheet **Rekap Item**: rerata & bobot tiap pernyataan → sumber Tabel 6 & Gambar 11.
- Kategori (Bangor): ≥80,3 Excellent · 68–80,3 Good · 51–68 OK · <51 Poor.

## Kuesioner SUS via Google Form

1. Buka [script.google.com](https://script.google.com) → *New project*, tempel isi `google_form_sus.gs`, Save.
2. Pilih fungsi `buatFormSUS` → *Run* → izinkan akses. Link form, link edit, dan link
   spreadsheet muncul di *Execution log*.
3. Sebarkan link form. Di spreadsheet, sheet **Skor SUS** menghitung skor, kategori,
   ringkasan, dan rekap per item secara otomatis.
4. Untuk `buat_gambar.py sus`: salin kolom **B–K** (P1–P10) dari sheet *Skor SUS* ke sel kuning
   sheet *Data* di `kuesioner_sus.xlsx` (mulai baris 4, *Paste values only*).

## Urutan menjalankan

1. Kumpulkan consent + dataset (enroll/test/spoof).
2. `evaluate_accuracy.py` (`--max-dim 640` & `0`) + `evaluate_antispoof.py` → Tabel 3, Gambar 7–8;
   `buat_gambar.py mtcnn` & `pose` → Gambar 2 & 6.
3. Uji black-box di sistem live → isi `form_blackbox.csv`.
4. Uji kelas → responden isi SUS (`buat_gambar.py sus` → Tabel 6, Gambar 11) + ekspor
   `face_verify_logs` (kolom `Durasi (ms)` = waktu respons, confidence nyata).
5. Isi placeholder artikel dari hasil di atas + terapkan [../docs/REVISI_ARTIKEL.md](../docs/REVISI_ARTIKEL.md).
