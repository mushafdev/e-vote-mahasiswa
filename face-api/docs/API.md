# API Documentation — Face Recognition Service

**Base URL:** `http://127.0.0.1:5055`  
**Autentikasi:** Semua endpoint `/api/face/*` wajib menyertakan header:
```
X-API-Key: <API_SECRET_KEY dari .env>
```

---

## Daftar Endpoint

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/health` | Cek status server & konfigurasi |
| POST | `/api/face/register` | Daftarkan wajah pemilih |
| POST | `/api/face/verify` | Verifikasi wajah pemilih |
| GET | `/api/face/status/{pemilih_id}` | Cek apakah pemilih sudah terdaftar |
| DELETE | `/api/face/delete/{pemilih_id}` | Hapus data wajah pemilih |

---

## Format Umum Response

### Sukses
```json
{
  "success": true,
  "message": "...",
  ...
}
```

### Error
```json
{
  "success": false,
  "message": "Deskripsi error"
}
```

---

## Endpoint Detail

---

### GET `/api/health`
Cek status server. Tidak butuh API key.

**Response `200`:**
```json
{
  "status": "ok",
  "model": "Facenet512",
  "detector": "opencv"
}
```

---

### POST `/api/face/register`
Ekstrak embedding wajah dari gambar dan simpan untuk pemilih tertentu.  
Dipanggil saat **registrasi akun** pemilih di Laravel.

**Request Body (JSON):**
```json
{
  "pemilih_id": "51661",
  "image_base64": "data:image/jpeg;base64,/9j/4AAQSkZJRgAB..."
}
```

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `pemilih_id` | string | Ya | ID pemilih dari tabel `pemilihs` Laravel |
| `image_base64` | string | Ya | Gambar wajah, base64, boleh dengan/tanpa prefix data URI |

**Response `200` — Berhasil:**
```json
{
  "success": true,
  "message": "Wajah berhasil didaftarkan",
  "pemilih_id": "51661"
}
```

**Response `422` — Wajah tidak terdeteksi:**
```json
{
  "success": false,
  "message": "Wajah tidak terdeteksi: Face could not be detected..."
}
```

**Response `422` — Input tidak valid:**
```json
{
  "success": false,
  "message": "pemilih_id wajib diisi"
}
```

**Response `401` — API key salah:**
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

> **Catatan:** Jika pemilih_id sudah punya embedding sebelumnya, data lama akan ditimpa (overwrite).

---

### POST `/api/face/verify`
Verifikasi wajah dari gambar baru dengan embedding yang sudah tersimpan.  
Dipanggil saat **login** atau saat **submit vote** di Laravel.

**Request Body (JSON):**
```json
{
  "pemilih_id": "51661",
  "image_base64": "data:image/jpeg;base64,/9j/4AAQSkZJRgAB..."
}
```

**Response `200` — Wajah cocok:**
```json
{
  "success": true,
  "match": true,
  "confidence": 87.43,
  "threshold": 60.0,
  "message": "Wajah cocok"
}
```

**Response `200` — Wajah tidak cocok:**
```json
{
  "success": true,
  "match": false,
  "confidence": 41.20,
  "threshold": 60.0,
  "message": "Wajah tidak cocok"
}
```

| Field | Tipe | Keterangan |
|---|---|---|
| `match` | boolean | `true` jika wajah terverifikasi |
| `confidence` | float | Skor kemiripan dalam persen (0–100) |
| `threshold` | float | Batas minimum confidence untuk diterima |

**Response `422` — Embedding tidak ditemukan:**
```json
{
  "success": false,
  "message": "Embedding tidak ditemukan"
}
```

> **Catatan:** `success: true` tidak berarti wajah cocok. Selalu cek field `match`.  
> Gunakan `confidence` sebagai data untuk analisis penelitian.

---

### GET `/api/face/status/{pemilih_id}`
Cek apakah pemilih sudah memiliki embedding wajah terdaftar.

**Contoh Request:**
```
GET /api/face/status/51661
X-API-Key: evote_face_secret_2024
```

**Response `200`:**
```json
{
  "success": true,
  "pemilih_id": "51661",
  "registered": true
}
```

---

### DELETE `/api/face/delete/{pemilih_id}`
Hapus data embedding wajah pemilih.  
Dipanggil saat akun pemilih dihapus dari admin Laravel.

**Contoh Request:**
```
DELETE /api/face/delete/51661
X-API-Key: evote_face_secret_2024
```

**Response `200` — Berhasil dihapus:**
```json
{
  "success": true,
  "message": "Embedding dihapus"
}
```

**Response `200` — Tidak ditemukan:**
```json
{
  "success": false,
  "message": "Embedding tidak ditemukan"
}
```

---

## Panduan Format Gambar

- **Format:** JPEG atau PNG
- **Ukuran:** Maksimal 2MB (disarankan < 500KB setelah kompresi)
- **Resolusi:** Minimal 200×200px, disarankan 640×480px
- **Kondisi:** Wajah menghadap kamera, pencahayaan cukup, tidak buram
- **Encoding:** Base64 — boleh dengan prefix (`data:image/jpeg;base64,...`) atau tanpa

**Contoh konversi di JavaScript (browser):**
```javascript
// Capture dari canvas WebRTC
const canvas = document.getElementById('canvas');
const imageBase64 = canvas.toDataURL('image/jpeg', 0.8); // sudah include prefix
```

**Contoh konversi di PHP (Laravel):**
```php
$imageBase64 = base64_encode(file_get_contents($request->file('foto')->path()));
```

---

## HTTP Status Codes

| Code | Arti |
|---|---|
| `200` | Request berhasil diproses (cek field `success` dan `match` untuk hasil) |
| `401` | API key tidak ada atau salah |
| `422` | Input tidak valid atau wajah tidak terdeteksi |
| `500` | Error internal server |

---

## Contoh Integrasi (cURL)

**Register:**
```bash
curl -X POST http://127.0.0.1:5055/api/face/register \
  -H "Content-Type: application/json" \
  -H "X-API-Key: evote_face_secret_2024" \
  -d '{
    "pemilih_id": "51661",
    "image_base64": "'$(base64 -i foto.jpg)'"
  }'
```

**Verify:**
```bash
curl -X POST http://127.0.0.1:5055/api/face/verify \
  -H "Content-Type: application/json" \
  -H "X-API-Key: evote_face_secret_2024" \
  -d '{
    "pemilih_id": "51661",
    "image_base64": "'$(base64 -i foto_baru.jpg)'"
  }'
```

---

## Model yang Didukung DeepFace

Dapat diganti via `FACE_MODEL` di `.env` — **perubahan model memerlukan re-registrasi semua wajah**.

| Model | Dimensi Embedding | Akurasi (LFW) | Kecepatan |
|---|---|---|---|
| `Facenet512` | 512 | 99.65% | Sedang ⭐ Rekomendasi |
| `ArcFace` | 512 | 99.41% | Sedang |
| `Facenet` | 128 | 99.20% | Cepat |
| `VGG-Face` | 2622 | 98.78% | Lambat |
| `DeepFace` | 4096 | 97.35% | Lambat |
