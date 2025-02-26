<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RumusanAkhirCpl;

class RumusanAkhirCplSeeder extends Seeder
{
    public function run()
    {
        RumusanAkhirCpl::create([
            'kode_cpl' => 'CPL001',
            'mata_kuliah' => 'Matematika',
            'kode_cpmk' => 'CPMK001',
            'skor_maksimal' => 100,
            'total_skor' => 80,
            // Tambahkan data lain sesuai struktur tabel Anda
        ]);

        // Tambahkan lebih banyak data jika perlu
    }
}

