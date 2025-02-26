<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Mengosongkan tabel mahasiswa
        DB::table('mahasiswas')->truncate();
        // Hapus semua data di tabel mahasiswas agar tidak ada data duplikat
        Mahasiswa::truncate();

        // Insert data tanpa menyetel id secara manual
        Mahasiswa::create([
            'nim' => 'G1F022048',
            'nama' => 'Intan Budiarty',
            'angkatan' => '2022',
        ]);

        Mahasiswa::create([
            'nim' => 'G1F022066',
            'nama' => 'Nabila Wijaya',
            'angkatan' => '2022',
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
