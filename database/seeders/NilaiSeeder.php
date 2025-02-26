<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nilai;

class NilaiSeeder extends Seeder
{
    public function run()
    {
        Nilai::insert([
            [
                'mahasiswa_id'=>150,
                'mata_kuliah_id'=>1,
                // 'nama_mk'=>lala,
                'kd_cpl'=>CPL-01,
                'kd_cpmk'=>CPMK01,
                'skor_maksimal'=>40,
                'total_skor'=>40,	
            ]
        ]);
    }
}
