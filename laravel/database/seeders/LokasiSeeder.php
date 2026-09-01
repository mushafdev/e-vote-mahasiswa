<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('lokasis')->insert([
             ['lokasi'=>'Fisik','inserted_by'=>1 ,'created_at' => now(), 'updated_at' => now()],
             ['lokasi'=>'Mitra','inserted_by'=>1 ,'created_at' => now(), 'updated_at' => now()],
             ['lokasi'=>'Transit','inserted_by'=>1 ,'created_at' => now(), 'updated_at' => now()],
             ['lokasi'=>'Virtual','inserted_by'=>1 ,'created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
