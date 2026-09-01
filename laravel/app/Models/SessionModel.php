<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionModel extends Model
{
    use HasFactory;

    //script di bawah ini ditambahkan jika
    //tidak mengikuti naming convention dari laravel
    protected $table = 'sessions';

}
