<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KelompokTimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('kelompok_tims')->insert([
             ['kelompok_tim'=>'Tim Rahmat','kategori'=>'utama','inserted_by'=>NULL ,'created_at' => now(), 'updated_at' => now()],
             ['kelompok_tim'=>'Tim Bambang','kategori'=>'utama','inserted_by'=>NULL ,'created_at' => now(), 'updated_at' => now()],
             ['kelompok_tim'=>'Relawan Berkah','kategori'=>'sayap','inserted_by'=>NULL ,'created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
