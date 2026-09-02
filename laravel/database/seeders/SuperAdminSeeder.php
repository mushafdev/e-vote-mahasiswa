<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    /**
     * Akun Super Admin + role Spatie.
     *
     * Otorisasi aplikasi memakai Spatie (guard 'user'), bukan kolom users.role.
     * Karena itu superadmin harus:
     *   1. login: level='a', active='Y'  (syarat AdminLoginController)
     *   2. punya role Spatie 'su'        (agar lolos middleware role:...|su|...)
     * Bypass SEMUA permission diberikan lewat Gate::before di AuthServiceProvider
     * yang mengembalikan true untuk pemilik role 'su'.
     *
     * @return void
     */
    public function run()
    {
        // Buat semua role admin (guard 'user') bila belum ada.
        foreach (['su', 'a', 'kp', 'p', 'v'] as $roleName) {
            Role::firstOrCreate([
                'name'       => $roleName,
                'guard_name' => 'user',
            ]);
        }

        // Buat / perbarui akun superadmin (idempoten berdasarkan username).
        // Atribut di-set satu per satu karena model Users memakai $guarded = ['*']
        // (mass assignment lewat create/updateOrCreate akan ditolak).
        $existing   = DB::table('users')->where('username', 'superadmin')->first();
        $superAdmin = $existing ? Users::find($existing->id) : new Users();

        $superAdmin->nama     = 'Super Admin';
        $superAdmin->email    = 'superadmin@gmail.com';
        $superAdmin->alamat   = 'Jl. Alamat';
        $superAdmin->kontak   = '081200000000';
        $superAdmin->level    = 'a';
        $superAdmin->role     = 'su';
        $superAdmin->username = 'superadmin';
        $superAdmin->password = Hash::make('12345678');
        $superAdmin->active   = 'Y';
        $superAdmin->save();

        // Assign role Spatie 'su' ke akun (hanya jika belum punya).
        if (! $superAdmin->hasRole('su')) {
            $superAdmin->assignRole('su');
        }

        // Bersihkan cache permission Spatie agar perubahan langsung berlaku.
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
