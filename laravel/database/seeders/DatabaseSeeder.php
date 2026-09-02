<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            KelompokTimSeeder::class,

            UnitKerjaSeeder::class,
            SuperAdminSeeder::class,
            UsersSeeder::class,
            PemilihSeeder::class,
            KandidatSeeder::class,
            SettingSeeder::class,
          ]);
    }
}
