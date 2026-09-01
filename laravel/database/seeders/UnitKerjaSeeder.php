<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('unit_kerjas')->insert([
             ['kode'=>'HO','unit_kerja'=>'Kantor Pusat','alamat'=>'Jl. Kantor Pusat','kabupaten'=>'Makassar','kontak'=>'085423444567','keterangan'=>NULL,'inserted_by'=>NULL ,'created_at' => now(), 'updated_at' => now()],
             ['kode'=>'DUM','unit_kerja'=>'Kantor Divisi Umum','alamat'=>'Jl. Gowa','kabupaten'=>'Gowa','kontak'=>'085111122345','keterangan'=>NULL,'inserted_by'=>NULL ,'created_at' => now(), 'updated_at' => now()],
             ['kode'=>'MRS','unit_kerja'=>'Kantor Cabang Maros','alamat'=>'Jl. Maros','kabupaten'=>'Maros','kontak'=>'085345122348','keterangan'=>NULL,'inserted_by'=>NULL ,'created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
