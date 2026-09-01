<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationOtp extends Model
{
    use HasFactory;
    protected $fillable = ['pemilih_id', 'role', 'otp', 'expire_at'];
}
