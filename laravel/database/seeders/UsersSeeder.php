<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insert([
             ['nama'=>'Lorem Ipsum','unit_kerja_id'=>NULL,'email'=>'mushafdev@gmail.com','alamat'=>'Jl. Alamat','kontak'=>'085423444567','photo'=>NULL,'level'=>'a','role'=>'a', 'username'=>'admin123','password' => Hash::make('12345678'),'inserted_by'=>19343 ,'created_at' => now(), 'updated_at' => now(),'active'=>'Y'],
             ['nama'=>'Alimuddin','unit_kerja_id'=>NULL,'email'=>'subadmin@gmail.com','alamat'=>'Jl. Jalan','kontak'=>'085234233111','photo'=>NULL,'level'=>'a','role'=>'sa', 'username'=>'admin123','password' => Hash::make('12345678'),'inserted_by'=>19343 ,'created_at' => now(), 'updated_at' => now(),'active'=>'Y'],
         ]);

    }
}
