# Panduan Integrasi dengan Laravel (e_vote)

Dokumen ini menjelaskan langkah-langkah menghubungkan sistem Face Recognition API
dengan project Laravel di `/Applications/MAMP/htdocs/e_vote`.

---

## 1. Konfigurasi .env Laravel

Tambahkan baris berikut ke `/Applications/MAMP/htdocs/e_vote/.env`:

```env
FACE_API_URL=http://127.0.0.1:5055
FACE_API_KEY=evote_face_secret_2024
```

---

## 2. Migrasi Database

Buat migration baru di Laravel untuk menambah kolom ke tabel `pemilihs`:

```bash
php artisan make:migration add_face_columns_to_pemilihs_table --table=pemilihs
```

Isi migration:
```php
public function up()
{
    Schema::table('pemilihs', function (Blueprint $table) {
        $table->boolean('face_registered')->default(false)->after('file_photo');
        $table->timestamp('face_registered_at')->nullable()->after('face_registered');
    });
}

public function down()
{
    Schema::table('pemilihs', function (Blueprint $table) {
        $table->dropColumn(['face_registered', 'face_registered_at']);
    });
}
```

```bash
php artisan migrate
```

---

## 3. Service Class di Laravel

Buat file `app/Services/FaceRecognitionService.php`:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FaceRecognitionService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.face_api.url');
        $this->apiKey  = config('services.face_api.key');
    }

    private function client()
    {
        return Http::withHeaders(['X-API-Key' => $this->apiKey])
                   ->timeout(15);
    }

    /** Daftarkan wajah pemilih. $imageBase64 boleh dengan/tanpa prefix. */
    public function register(string|int $pemilihId, string $imageBase64): array
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/api/face/register", [
                'pemilih_id'   => (string) $pemilihId,
                'image_base64' => $imageBase64,
            ]);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('[FaceAPI] register error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Face API tidak dapat dihubungi'];
        }
    }

    /** Verifikasi wajah. Return ['match' => bool, 'confidence' => float] */
    public function verify(string|int $pemilihId, string $imageBase64): array
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/api/face/verify", [
                'pemilih_id'   => (string) $pemilihId,
                'image_base64' => $imageBase64,
            ]);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('[FaceAPI] verify error: ' . $e->getMessage());
            return ['success' => false, 'match' => false, 'message' => 'Face API tidak dapat dihubungi'];
        }
    }

    /** Cek apakah pemilih sudah punya embedding. */
    public function isRegistered(string|int $pemilihId): bool
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/api/face/status/{$pemilihId}");
            return $response->json('registered', false);
        } catch (\Exception $e) {
            return false;
        }
    }

    /** Hapus embedding saat pemilih dihapus. */
    public function delete(string|int $pemilihId): bool
    {
        try {
            $response = $this->client()->delete("{$this->baseUrl}/api/face/delete/{$pemilihId}");
            return $response->json('success', false);
        } catch (\Exception $e) {
            return false;
        }
    }
}
```

---

## 4. Tambah Config di `config/services.php`

```php
'face_api' => [
    'url' => env('FACE_API_URL', 'http://127.0.0.1:5055'),
    'key' => env('FACE_API_KEY', ''),
],
```

---

## 5. Integrasi di Registrasi (LoginController)

Di method `registrasi_store`, setelah foto berhasil disimpan tambahkan:

```php
use App\Services\FaceRecognitionService;

// Setelah $pemilih->save() berhasil:
if ($pemilih->id && !empty($request->face_image)) {
    $faceService = new FaceRecognitionService();
    $result = $faceService->register($pemilih->id, $request->face_image);

    if ($result['success']) {
        $pemilih->face_registered = true;
        $pemilih->face_registered_at = now();
        $pemilih->save();
    }
}
```

Di form registrasi (Blade), tambahkan input tersembunyi dan kamera:
```html
<input type="hidden" id="face_image" name="face_image">
<!-- Lihat bagian Frontend di bawah -->
```

---

## 6. Integrasi di Voting (AccountController)

Sebelum menyimpan pilihan kandidat, verifikasi wajah dulu:

```php
use App\Services\FaceRecognitionService;

public function pilih_kandidat_update(Request $request)
{
    $pemilih = Auth::guard('pemilih')->user();

    // Verifikasi wajah jika sudah terdaftar
    if ($pemilih->face_registered) {
        if (empty($request->face_image)) {
            return response()->json(['message' => 'Verifikasi wajah diperlukan'], 422);
        }

        $faceService = new FaceRecognitionService();
        $result = $faceService->verify($pemilih->id, $request->face_image);

        if (!$result['success'] || !$result['match']) {
            return response()->json([
                'message' => 'Verifikasi wajah gagal. Confidence: ' . ($result['confidence'] ?? 0) . '%'
            ], 422);
        }
    }

    // Lanjut simpan pilihan seperti sebelumnya...
}
```

---

## 7. Frontend — Kamera & Capture (JavaScript)

Tambahkan komponen ini di halaman registrasi dan voting:

```html
<!-- Modal atau section kamera -->
<div id="camera-section">
    <video id="camera-video" autoplay playsinline width="320" height="240"></video>
    <canvas id="camera-canvas" width="320" height="240" style="display:none;"></canvas>
    <button type="button" id="btn-capture">Ambil Foto Wajah</button>
    <img id="preview-face" src="" style="display:none; width:160px; border-radius:8px;">
    <input type="hidden" id="face_image" name="face_image">
</div>

<script>
(function () {
    const video   = document.getElementById('camera-video');
    const canvas  = document.getElementById('camera-canvas');
    const preview = document.getElementById('preview-face');
    const input   = document.getElementById('face_image');
    const btnCap  = document.getElementById('btn-capture');

    // Aktifkan kamera
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
        .then(stream => { video.srcObject = stream; })
        .catch(err  => { alert('Kamera tidak dapat diakses: ' + err.message); });

    // Capture foto
    btnCap.addEventListener('click', function () {
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const base64 = canvas.toDataURL('image/jpeg', 0.85);
        input.value  = base64;
        preview.src  = base64;
        preview.style.display = 'block';
        video.style.display   = 'none';
        btnCap.textContent    = 'Ulangi Foto';
    });
})();
</script>
```

---

## 8. Alur Lengkap Sistem

```
[Registrasi]
Form → Upload KTP + foto KTP + capture wajah kamera
    → POST Laravel /login/authenticate-registrasi
    → Simpan data pemilih ke DB
    → POST Python API /api/face/register (face_image base64)
    → Update pemilihs.face_registered = true
    → Kirim email verifikasi

[Login]
Username + Password → OTP via email → (Opsional) Verifikasi wajah
    → Masuk ke dashboard

[Voting]
Pilih kandidat → Modal konfirmasi → Capture wajah kamera
    → POST Python API /api/face/verify
    → Jika match: simpan pilihan ke DB
    → Jika tidak match: tampilkan error + confidence score
```

---

## 9. Catatan Penting

- Python API harus **running** sebelum fitur face recognition di Laravel bisa digunakan
- Untuk development: jalankan `python run.py` di folder `face-recognition-api/`
- Jika Python API mati, voting tetap bisa dilakukan jika `face_registered = false`
- Tambahkan timeout handling di Laravel agar tidak hang jika API lambat
- Untuk penelitian: log semua nilai `confidence` ke tabel terpisah (`face_verify_logs`)
