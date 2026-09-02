<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KandidatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kandidats')->insert([
            [
                'no_urut' => 1,
                'no_anggota' => 'MHS-2024001',
                'nama' => 'Andi Pratama & Siti Nurhaliza',
                'file_photo' => NULL,
                'deskripsi' => 'Visi: Mewujudkan kampus yang inklusif dan berprestasi. Misi: Meningkatkan kegiatan kemahasiswaan, transparansi anggaran organisasi, dan fasilitas belajar mahasiswa.',
                'calon_kandidat_status' => 'Y',
                'kandidat_status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no_urut' => 2,
                'no_anggota' => 'MHS-2024002',
                'nama' => 'Budi Santoso & Rina Wijaya',
                'file_photo' => NULL,
                'deskripsi' => 'Visi: Kampus kolaboratif berbasis teknologi. Misi: Digitalisasi layanan mahasiswa, penguatan minat & bakat, serta kerja sama antar organisasi mahasiswa.',
                'calon_kandidat_status' => 'Y',
                'kandidat_status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no_urut' => 3,
                'no_anggota' => 'MHS-2024003',
                'nama' => 'Chandra Kusuma & Dewi Lestari',
                'file_photo' => NULL,
                'deskripsi' => 'Visi: Mahasiswa aktif, kritis, dan berdaya. Misi: Advokasi hak mahasiswa, pengembangan jiwa kewirausahaan, dan program pengabdian masyarakat.',
                'calon_kandidat_status' => 'Y',
                'kandidat_status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no_urut' => 4,
                'no_anggota' => 'MHS-2024004',
                'nama' => 'Eko Nugroho & Fitri Handayani',
                'file_photo' => NULL,
                'deskripsi' => 'Visi: Membangun solidaritas dan prestasi mahasiswa. Misi: Optimalisasi UKM, peningkatan literasi digital, dan penguatan kesejahteraan mahasiswa.',
                'calon_kandidat_status' => 'Y',
                'kandidat_status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
