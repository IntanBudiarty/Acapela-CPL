<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cek dan buat role admin jika belum ada
        if (!Role::where('name', 'admin')->exists()) {
            Role::create([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);
        }

        // Cek dan buat role dosen jika belum ada
        if (!Role::where('name', 'dosen')->exists()) {
            Role::create([
                'name' => 'dosen',
                'guard_name' => 'web',
            ]);
        }
    }
}
