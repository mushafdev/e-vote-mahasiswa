<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\FaceVerifyLog;

class FaceRecognitionService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.face_api.url', 'http://127.0.0.1:5055');
        $this->apiKey  = config('services.face_api.key', '');
    }

    // Default timeout untuk verify (login/voting). Dinaikkan dari 15s → 45s agar
    // saat banyak yang login/memilih bersamaan, request yang mengantre di gunicorn
    // tidak keburu timeout (verify 1 foto relatif cepat, tapi bisa antre saat burst).
    private function client(int $timeout = 45)
    {
        return Http::withHeaders(['X-API-Key' => $this->apiKey])->timeout($timeout);
    }

    /**
     * Daftarkan wajah pemilih.
     * $imagesBase64: array 1–5 foto (images_base64) atau string tunggal (image_base64)
     */
    public function register($pemilihId, $imagesBase64): array
    {
        try {
            $payload = ['pemilih_id' => (string) $pemilihId];
            if (is_array($imagesBase64)) {
                $payload['images_base64'] = $imagesBase64;
            } else {
                $payload['images_base64'] = [$imagesBase64];
            }
            // Registrasi memproses beberapa foto (embedding) secara berurutan
            // dengan MTCNN. Saat burst 40 serentak, ekor antrean selesai ~156s
            // (hasil load test), jadi beri timeout 180s agar semua tuntas tanpa
            // rollback. Selaras dengan GUNICORN_TIMEOUT=180.
            $response = $this->client(180)->post("{$this->baseUrl}/api/face/register", $payload);
            return $response->json() ?? ['success' => false, 'message' => 'Respon tidak valid'];
        } catch (\Exception $e) {
            Log::error('[FaceAPI] register error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Face API tidak dapat dihubungi'];
        }
    }

    /**
     * Verifikasi wajah dan simpan log otomatis.
     * $aksi: 'verify_login' | 'verify_vote'
     */
    public function verify($pemilihId, string $imageBase64, string $aksi = 'verify_vote'): array
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/api/face/verify", [
                'pemilih_id'   => (string) $pemilihId,
                'image_base64' => $imageBase64,
            ]);
            $result = $response->json() ?? ['success' => false, 'match' => false, 'message' => 'Respon tidak valid'];
        } catch (\Exception $e) {
            Log::error('[FaceAPI] verify error: ' . $e->getMessage());
            $result = ['success' => false, 'match' => false, 'message' => 'Face API tidak dapat dihubungi'];
        }

        // Simpan log untuk data penelitian
        try {
            FaceVerifyLog::create([
                'pemilih_id'      => $pemilihId,
                'aksi'            => $aksi,
                'match'           => $result['match'] ?? false,
                'confidence'      => $result['confidence'] ?? 0,
                'threshold'       => $result['threshold'] ?? 0,
                'is_real'         => $result['is_real'] ?? true,
                'spoof_score'     => $result['spoof_score'] ?? 1.0,
                'antispoof_label' => $result['antispoof_label'] ?? 'real',
                'keterangan'      => $result['message'] ?? null,
                'ip_address'      => request()->ip(),
            ]);
        } catch (\Exception $e) {
            Log::warning('[FaceAPI] Gagal simpan log: ' . $e->getMessage());
        }

        return $result;
    }

    /** Cek apakah pemilih sudah punya embedding terdaftar. */
    public function isRegistered($pemilihId): bool
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/api/face/status/{$pemilihId}");
            return $response->json('registered', false);
        } catch (\Exception $e) {
            return false;
        }
    }

    /** Hapus embedding saat pemilih dihapus dari sistem. */
    public function delete($pemilihId): bool
    {
        try {
            $response = $this->client()->delete("{$this->baseUrl}/api/face/delete/{$pemilihId}");
            return $response->json('success', false);
        } catch (\Exception $e) {
            return false;
        }
    }
}
