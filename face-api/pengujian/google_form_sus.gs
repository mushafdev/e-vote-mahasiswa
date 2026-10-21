/**
 * Generator Google Form — Kuesioner SUS (System Usability Scale)
 * Penelitian: Sistem E-Voting Akademik dengan Verifikasi Wajah — UNM
 *
 * CARA PAKAI
 *   1. Buka https://script.google.com → New project.
 *   2. Hapus isi Code.gs, tempel seluruh isi file ini, lalu Save.
 *   3. Pilih fungsi `buatFormSUS` di toolbar → Run → izinkan akses (Forms, Sheets, Drive).
 *   4. Buka View → Logs (Execution log): berisi link form (untuk responden),
 *      link edit form, dan link spreadsheet hasil.
 *
 * HASIL
 *   - Google Form: identitas singkat + 10 pernyataan SUS (skala 1–5, wajib) + saran.
 *   - Spreadsheet tertaut, dengan sheet tambahan "Skor SUS" yang terhitung otomatis:
 *       kolom B–K = P1–P10 (format sama dgn sheet Data di kuesioner_sus.xlsx → bisa
 *       langsung disalin ke sel kuning mulai baris 4), skor SUS & kategori per responden,
 *       ringkasan (jumlah responden, rata-rata, kategori), dan rekap rerata per item.
 *
 * Teks pernyataan & rumus skor mengikuti docs/RENCANA_PENGUJIAN_ARTIKEL.md §5.
 */

const JUDUL_FORM = 'Kuesioner Usability (SUS) — E-Voting Face Recognition UNM';

const PERNYATAAN_SUS = [
  'Saya rasa saya ingin lebih sering menggunakan sistem ini.',
  'Saya menganggap sistem ini terlalu rumit.',
  'Saya rasa sistem ini mudah digunakan.',
  'Saya rasa saya butuh bantuan orang teknis untuk menggunakan sistem ini.',
  'Saya menemukan berbagai fungsi dalam sistem ini terintegrasi dengan baik.',
  'Saya rasa terlalu banyak ketidakkonsistenan dalam sistem ini.',
  'Saya rasa kebanyakan orang akan cepat belajar menggunakan sistem ini.',
  'Saya merasa sistem ini janggal/menyulitkan saat digunakan.',
  'Saya merasa sangat percaya diri menggunakan sistem ini.',
  'Saya perlu belajar banyak hal sebelum bisa menggunakan sistem ini.',
];

function buatFormSUS() {
  const form = FormApp.create(JUDUL_FORM)
    .setDescription(
      'Kuesioner ini mengukur kemudahan penggunaan (usability) sistem e-voting dengan ' +
      'verifikasi wajah. Isi SETELAH Anda mencoba sistem (login, verifikasi wajah, memilih).\n\n' +
      'Jawaban bersifat anonim, hanya digunakan untuk penelitian, dan dilaporkan secara ' +
      'agregat. Tidak ada jawaban benar atau salah — jawablah sesuai pengalaman Anda.\n\n' +
      'Skala: 1 = Sangat Tidak Setuju · 2 = Tidak Setuju · 3 = Netral · 4 = Setuju · ' +
      '5 = Sangat Setuju. Waktu pengisian ± 3 menit.'
    )
    .setProgressBar(true)
    .setShowLinkToRespondAgain(false)
    .setConfirmationMessage('Terima kasih! Jawaban Anda sudah tercatat.');

  // ── Bagian 1: identitas singkat (tanpa nama/NIM agar tetap anonim) ──────────
  form.addSectionHeaderItem().setTitle('Data Responden');

  form.addTextItem().setTitle('Program Studi').setRequired(true);

  const angkatan = new Date().getFullYear();
  form.addListItem()
    .setTitle('Angkatan')
    .setChoiceValues([0, 1, 2, 3, 4, 5, 6].map(function (i) { return String(angkatan - i); })
      .concat(['Lainnya']))
    .setRequired(true);

  form.addMultipleChoiceItem()
    .setTitle('Apakah Anda sudah mencoba sistem e-voting (login, verifikasi wajah, dan memilih)?')
    .setChoiceValues(['Ya, sudah mencoba sampai selesai', 'Ya, tetapi tidak sampai selesai'])
    .setRequired(true);

  // ── Bagian 2: 10 pernyataan SUS ─────────────────────────────────────────────
  form.addPageBreakItem()
    .setTitle('Pernyataan Usability (SUS)')
    .setHelpText('Pilih angka yang paling sesuai dengan pengalaman Anda menggunakan sistem.');

  PERNYATAAN_SUS.forEach(function (teks, i) {
    form.addScaleItem()
      .setTitle((i + 1) + '. ' + teks)
      .setBounds(1, 5)
      .setLabels('Sangat Tidak Setuju', 'Sangat Setuju')
      .setRequired(true);
  });

  form.addParagraphTextItem()
    .setTitle('Kritik / saran untuk sistem (opsional)')
    .setRequired(false);

  // ── Spreadsheet hasil + sheet "Skor SUS" ────────────────────────────────────
  const ss = SpreadsheetApp.create('Hasil ' + JUDUL_FORM);
  form.setDestination(FormApp.DestinationType.SPREADSHEET, ss.getId());
  SpreadsheetApp.flush();
  buatSheetSkor_(ss);

  Logger.log('Link untuk responden : ' + form.getPublishedUrl());
  Logger.log('Link edit form       : ' + form.getEditUrl());
  Logger.log('Spreadsheet hasil    : ' + ss.getUrl());
}

/** Membuat sheet "Skor SUS" yang menghitung skor dari sheet jawaban form. */
function buatSheetSkor_(ss) {
  const resp = ss.getSheets().filter(function (s) { return s.getFormUrl(); })[0];
  if (!resp) throw new Error('Sheet jawaban form tidak ditemukan.');
  const nama = "'" + resp.getName().replace(/'/g, "''") + "'";

  // Cari kolom tiap pernyataan SUS lewat judul header (judul diawali "1. ", "2. ", ...).
  const header = resp.getRange(1, 1, 1, resp.getLastColumn()).getValues()[0];
  const kolomP = PERNYATAAN_SUS.map(function (teks, i) {
    const idx = header.indexOf((i + 1) + '. ' + teks);
    if (idx < 0) throw new Error('Kolom pernyataan ' + (i + 1) + ' tidak ditemukan di ' + nama);
    return kolom_(idx + 1);
  });

  // Sheet bawaan "Sheet1" dari SpreadsheetApp.create dipakai ulang sebagai sheet skor.
  const sh = ss.getSheets().filter(function (s) { return !s.getFormUrl(); })[0] ||
    ss.insertSheet();
  sh.setName('Skor SUS');
  ss.setActiveSheet(sh);
  ss.moveActiveSheet(1);

  const judul = ['Responden'].concat(PERNYATAAN_SUS.map(function (_, i) { return 'P' + (i + 1); }),
    ['Skor SUS', 'Kategori']);
  sh.getRange(1, 1, 1, judul.length).setValues([judul]).setFontWeight('bold')
    .setBackground('#d9ead3');

  // A: kode responden R01, R02, ... (hanya baris yang berisi jawaban).
  sh.getRange('A2').setFormula(
    '=ARRAYFORMULA(IF(LEN(' + nama + '!A2:A), "R"&TEXT(ROW(' + nama + '!A2:A)-1, "00"), ""))');

  // B–K: salin jawaban P1–P10 dari sheet form.
  kolomP.forEach(function (k, i) {
    sh.getRange(2, i + 2).setFormula('=ARRAYFORMULA(' + nama + '!' + k + '2:' + k + ')');
  });

  // L: skor SUS — item ganjil (skor−1), item genap (5−skor), jumlah × 2,5.
  sh.getRange('L2').setFormula(
    '=ARRAYFORMULA(IF(B2:B="", "", ((B2:B-1)+(5-C2:C)+(D2:D-1)+(5-E2:E)+(F2:F-1)' +
    '+(5-G2:G)+(H2:H-1)+(5-I2:I)+(J2:J-1)+(5-K2:K))*2.5))');

  // M: kategori (Bangor et al.).
  sh.getRange('M2').setFormula(
    '=ARRAYFORMULA(IF(L2:L="", "", IF(L2:L>=80.3, "Excellent (A)", IF(L2:L>=68, "Good (B)",' +
    ' IF(L2:L>=51, "OK (C)", "Poor")))))');

  // Ringkasan.
  sh.getRange('O1:P1').setValues([['Ringkasan', '']]).setFontWeight('bold')
    .setBackground('#d9ead3');
  sh.getRange('O2:O4').setValues([['Jumlah responden'], ['Skor SUS rata-rata'], ['Kategori']]);
  sh.getRange('P2').setFormula('=COUNT(L2:L)');
  sh.getRange('P3').setFormula('=IFERROR(ROUND(AVERAGE(L2:L), 2), "")');
  sh.getRange('P4').setFormula(
    '=IF(P3="", "", IF(P3>=80.3, "Excellent (A)", IF(P3>=68, "Good (B)", ' +
    'IF(P3>=51, "OK (C)", "Poor"))))');

  // Rekap per item (sumber Tabel 6 / Gambar 11).
  sh.getRange('O6:R6').setValues([['Item', 'Pernyataan', 'Skor rerata', 'Bobot kontribusi']])
    .setFontWeight('bold').setBackground('#d9ead3');
  PERNYATAAN_SUS.forEach(function (teks, i) {
    const r = 7 + i;
    const k = kolom_(i + 2);
    sh.getRange(r, 15, 1, 2).setValues([['P' + (i + 1), teks]]);
    sh.getRange(r, 17).setFormula('=IFERROR(ROUND(AVERAGE(' + k + '2:' + k + '), 2), "")');
    // Item ganjil (positif): rerata−1; item genap (negatif): 5−rerata.
    sh.getRange(r, 18).setFormula(i % 2 === 0
      ? '=IF(Q' + r + '="", "", Q' + r + '-1)'
      : '=IF(Q' + r + '="", "", 5-Q' + r + ')');
  });

  sh.setFrozenRows(1);
  sh.autoResizeColumns(1, 18);
}

/** 1 → A, 27 → AA */
function kolom_(n) {
  let s = '';
  while (n > 0) {
    const m = (n - 1) % 26;
    s = String.fromCharCode(65 + m) + s;
    n = Math.floor((n - 1) / 26);
  }
  return s;
}
