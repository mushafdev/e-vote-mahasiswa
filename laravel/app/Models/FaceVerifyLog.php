<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaceVerifyLog extends Model
{
    protected $fillable = [
        'pemilih_id', 'aksi', 'match', 'confidence', 'threshold',
        'is_real', 'spoof_score', 'antispoof_label', 'keterangan', 'ip_address',
    ];

    public function pemilih()
    {
        return $this->belongsTo(Pemilih::class, 'pemilih_id');
    }
}
