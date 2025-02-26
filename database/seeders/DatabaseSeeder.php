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
            RoleSeeder::class,
            UserSeeder::class,
            DosenAdminsTableSeeder::class,
            MahasiswaSeeder::class,
            TahunAjaransTableSeeder::class,
            MataKuliahsTableSeeder::class,
            KRSTableSeeder::class,
            CplsTableSeeder::class,
            CpmksTableSeeder::class,
            BtpsTableSeeder::class,
            BobotcplsTableSeeder::class,
            NilaisTableSeeder::class,
        ]);
    }
}
