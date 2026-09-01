<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PemilihSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('pemilihs')->insert([
             ['nama'=>'Basuki','email'=>'mushafdev@gmail.com','alamat'=>'Jl. Alamat','telp'=>'085423444567','photo'=>NULL,'level'=>'dpt','role'=>'dpt', 'username'=>'pemilih','password' => Hash::make('12345678'),'inserted_by'=>19343 ,'created_at' => now(), 'updated_at' => now(),'aktif'=>'Y','verifikasi'=>'Y','email_verifikasi'=>'Y'],
          ]);

    }
}
