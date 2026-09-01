<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Teams extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'teams';
    protected $dates = ['deleted_at'];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    // public function getAuthPassword()
    // {
    //     return $this->password;
    // }
}
