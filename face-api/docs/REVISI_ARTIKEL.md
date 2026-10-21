# Daftar Revisi Artikel — Penyelarasan dengan Sistem Nyata

Draft acuan: *Draft Artikel Voice Face-Recognition (Revisi Sistem Nyata) - BACKUP 20260922-2351.docx*.
Setiap butir berisi lokasi, kalimat sekarang (dikutip singkat), dan usulan pengganti. Tempelkan
manual ke docx. Butir bertanda **[setelah uji]** baru bisa difinalkan setelah hasil pengujian ada.

---

## 1. Anti-spoofing hanya saat verifikasi (bukan registrasi)

Sistem: `ANTISPOOF_ON_REGISTER=false`; liveness berjalan saat **verifikasi login dan pemungutan suara**.

| Lokasi | Sekarang | Usulan |
|---|---|---|
| 3.3, fitur (2) | "…dirata-ratakan menjadi satu template, dilengkapi pemeriksaan anti-spoofing (liveness detection)" | "…dirata-ratakan menjadi satu template embedding." (hapus frasa anti-spoofing) |
| 3.6, paragraf 3 | "…mekanisme anti-spoofing (liveness detection) yang dijalankan pada setiap registrasi maupun verifikasi…" | "…mekanisme anti-spoofing (liveness detection) yang dijalankan pada setiap verifikasi wajah, yaitu saat login dan saat pemungutan suara, sehingga…" |

Tambahan (opsional, memperkuat argumen) di 2.4 setelah alur: *"Pemeriksaan liveness sengaja
ditempatkan pada titik verifikasi, bukan registrasi: template hasil registrasi tetap harus
dicocokkan dengan wajah live yang lolos liveness setiap kali login dan memilih, sehingga titik
verifikasi merupakan pertahanan yang menentukan."*

## 2. Hapus klaim akurasi "pada berbagai kondisi"

Pengujian akurasi tidak dipecah per kondisi (pencahayaan/aksesori), jadi klaimnya dihapus.

| Lokasi | Sekarang | Usulan |
|---|---|---|
| Introduction, tujuan (2) | "mengevaluasi akurasi model face recognition dalam mengidentifikasi wajah mahasiswa pada berbagai kondisi" | "mengevaluasi akurasi verifikasi wajah mahasiswa menggunakan metrik akurasi, presisi, recall, F1-score, FAR, FRR, EER, dan AUC" |
| 3.6, paragraf 1 | "…detection rate [ ] dalam berbagai kondisi pose dan pencahayaan [16]" | "…detection rate [ ] pada lima variasi pose registrasi [16]" |
| 3.6, paragraf 3 | "Tantangan utama yang dihadapi selama pengujian adalah variasi pencahayaan ekstrem dan penggunaan aksesori wajah…" | Pindahkan ke keterbatasan: "Penelitian ini belum mengevaluasi akurasi secara terpisah untuk kondisi pencahayaan ekstrem maupun aksesori wajah (kacamata, masker), yang menurut Essien dan Ansa [24] dapat menurunkan tingkat kepercayaan verifikasi." |

Saran penelitian lanjutan (3) di Kesimpulan sudah sejalan — biarkan.

## 3. Ambang 0,60 divalidasi, bukan "ditemukan" dari ROC

Ambang 0,60 (jarak 0,40) ditetapkan saat desain, lalu diuji. Menulisnya sebagai hasil ROC
tidak sesuai urutan kerja dan mudah dipertanyakan reviewer.

| Lokasi | Sekarang | Usulan |
|---|---|---|
| 3.2, setelah Tabel 3 | "Ambang optimal ditetapkan pada nilai cosine similarity 0,60 … setelah analisis menggunakan kurva ROC…" | "Ambang operasional sistem, yaitu cosine similarity 0,60 (setara jarak 0,40), divalidasi menggunakan kurva ROC pada Gambar 7 dengan AUC [ ] dan EER [ ]% (ambang EER [ ])." **[setelah uji]** |
| 3.2, paragraf Gambar 8 | "Pemisahan kedua distribusi menjadi dasar penetapan ambang 0,60…" | "Pemisahan kedua distribusi menunjukkan bahwa ambang 0,60 berada di antara puncak distribusi genuine dan impostor, dengan FAR [ ]% dan FRR [ ]%." **[setelah uji]** |

Jika ambang EER ternyata jauh dari 0,60, bahas trade-off-nya (sistem e-voting memprioritaskan
FAR rendah).

## 4. Klaim keamanan yang terlalu mutlak

| Lokasi | Sekarang | Usulan |
|---|---|---|
| 3.3, paragraf setelah Gambar 9 | "…sistem berhasil menolak seluruh upaya bypass autentikasi dalam pengujian keamanan, termasuk percobaan menggunakan foto cetakan dan video replay." | "Pada pengujian anti-spoofing, sistem menolak [ ]% serangan presentasi (APCER [ ]%, BPCER [ ]%, ACER [ ]%). Seluruh [ ] skenario bypass autentikasi pada pengujian black-box [berhasil/…] ditolak." **[setelah uji]** — pakai "seluruh" hanya jika APCER = 0 dan semua skenario keamanan Berhasil. |
| Abstract, Research Implications | "…sistem menolak serangan foto cetak dan video replay…" | Sesuaikan dengan angka APCER. **[setelah uji]** |

## 5. Alur login sesuai sistem

Sistem: login **username** + password (dilindungi captcha Cloudflare Turnstile) → verifikasi wajah
login → pilih kandidat → **verifikasi wajah lagi saat mengirim suara** → simpan terenkripsi.
Tidak ada langkah OTP pada alur login aktif.

| Lokasi | Sekarang | Usulan |
|---|---|---|
| 2.4, alur (a)–(f) | "(a) mahasiswa login menggunakan NIM dan password; (b) sistem mengaktifkan kamera…" | "(a) mahasiswa login menggunakan username dan password yang dilindungi captcha; (b) sistem mengaktifkan kamera dan melakukan verifikasi wajah pertama sebelum mahasiswa masuk ke halaman pemilihan; (c) …(liveness, seperti sekarang)…; (d) …; (e) setelah memilih kandidat, mahasiswa kembali diverifikasi wajahnya sebelum suara dikirim; apabila verifikasi berhasil (cosine similarity ≥ 0,60), suara disimpan; (f) …" |
| Gambar 5 (flowchart) | Login → Capture Wajah → … → Pilih Kandidat → Simpan | Tambahkan verifikasi kedua: Login → Verifikasi Wajah (login) → Pilih Kandidat → Verifikasi Wajah (voting) → Simpan Suara Terenkripsi |

## 6. Cakupan black-box dan kebutuhan non-fungsional

Form black-box sekarang **33 skenario**: Login & Autentikasi 9, Registrasi Wajah 5, Verifikasi
Face Recognition 4, Pemungutan Suara 3, Pencegahan Double Voting 1, Rekapitulasi 2,
**Keamanan (Bypass Autentikasi) 9**. Fitur admin (CRUD kandidat/pemilih, pengaturan) tidak diuji.

| Lokasi | Sekarang | Usulan |
|---|---|---|
| 2.5 | "…pengujian fungsional black-box testing pada [ ] skenario uji yang mencakup seluruh fitur sistem, serta pengujian keamanan terhadap upaya bypass autentikasi…" | "…pengujian black-box pada 33 skenario uji yang mencakup alur utama pemilih (login, registrasi wajah, verifikasi, pemungutan suara, pencegahan double voting, rekapitulasi) serta 9 skenario keamanan terhadap upaya bypass autentikasi…" |
| 3.4 | "…[ ] skenario uji yang mencakup seluruh fitur dan alur utama sistem." | "…33 skenario uji yang mencakup alur utama pemilih dan skenario keamanan." |
| Tabel 5 (saran) | 6 modul | Tambah baris modul **Keamanan (Bypass Autentikasi)**; jumlah skenario per modul seperti di atas. |
| 2.1 / Tabel 1 | Non-fungsional: "kompatibilitas lintas perangkat" | Tidak diuji → hapus dari daftar, atau tetap tulis sebagai kebutuhan tetapi jangan diklaim terpenuhi di Hasil. |

## 7. Waktu respons verifikasi

Data dari kolom baru `face_verify_logs.durasi_ms` (ekspor CSV di menu Log Verifikasi Wajah).
Durasi diukur di server Laravel: dari permintaan ke Face API sampai respons diterima
(anti-spoofing + ekstraksi embedding + pencocokan + jaringan internal). Waktu unggah foto dari
browser **tidak** termasuk.

| Lokasi | Sekarang | Usulan |
|---|---|---|
| 3.3 | "Waktu respons rata-rata untuk proses verifikasi wajah adalah [ ] detik." | "Waktu respons rata-rata verifikasi wajah, diukur di sisi server dari permintaan ke layanan face recognition hingga respons diterima, adalah [ ] detik (n = [ ] verifikasi selama uji coba; median [ ] detik)." **[setelah uji]** |
| 2.1 | "kecepatan respons (<3 detik)" | Tetap, tetapi sebutkan bahwa yang diukur adalah waktu proses verifikasi di server. |

## 8. Gambar 6 diganti menjadi detection rate per pose

Karena tiap subjek pasti punya 5 pose, "distribusi pose" selalu ±20% per pose dan tidak
informatif.

| Lokasi | Sekarang | Usulan |
|---|---|---|
| 3.1, kalimat terakhir | "Distribusi citra berdasarkan lima variasi pose ditampilkan pada Gambar 6." | "Detection rate MTCNN untuk masing-masing pose registrasi ditampilkan pada Gambar 6; pose [menunduk/mendongak] memiliki detection rate terendah ([ ]%)." **[setelah uji]** |
| Judul Gambar 6 | "Distribusi Dataset Wajah berdasarkan Lima Variasi Pose…" | "Detection Rate MTCNN per Pose Registrasi (Depan, Kiri, Kanan, Menunduk, Mendongak)" |

Sumber: `python scripts/buat_gambar.py pose --log ./eval_640/detection_log.csv`.

## 9. Gambar 11 — warna berdasarkan bobot kontribusi

Saran di draft mewarnai batang berdasarkan skor mentah (hijau ≥ 4). Pada item genap (pernyataan
negatif), skor tinggi justru buruk, jadi item negatif yang bagus akan tampil merah. Skrip
mewarnai berdasarkan **bobot kontribusi** (0–4) dan menandai item negatif dengan (−).

Tambahkan di keterangan Gambar 11: *"Item bertanda (−) merupakan pernyataan negatif; skor rendah
menunjukkan penilaian baik."*

## 10. Gambar 2 — izin wajah

Gambar 2 menampilkan wajah subjek. Gunakan subjek yang memberi izin publikasi pada lembar
informed consent, atau jalankan skrip dengan `--blur`.

---

## Di luar artikel: temuan keamanan pada sistem

Endpoint voting lama `/pilih-kandidat-update` dan `/pilih-kandidat-2-update` masih aktif.
`/pilih-kandidat-2-update` **tidak memverifikasi wajah** dan menyimpan pilihan sebagai plaintext
(`calon_kandidat_2_id`). Rekap final tidak terpengaruh karena hanya membaca `vote_enc`, tetapi
skenario black-box no. 33 akan **Gagal** dan reviewer bisa mempertanyakan klaim enkripsi.
Rekomendasi: nonaktifkan kedua route tersebut sebelum pengujian.
