<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;


class Pemilih extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    protected $dates = ['deleted_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Enkripsi pilihan suara (kandidat_id) menjadi ciphertext AES-256.
     */
    public static function encryptVote($kandidatId): string
    {
        return Crypt::encryptString((string) $kandidatId);
    }

    /**
     * Dekripsi ciphertext vote_enc kembali menjadi kandidat_id (int) atau null
     * bila kosong/tidak valid. Aman dipakai saat rekapitulasi & tampilan detail.
     */
    public static function decryptVote($cipher): ?int
    {
        if (empty($cipher)) {
            return null;
        }
        try {
            return (int) Crypt::decryptString($cipher);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
