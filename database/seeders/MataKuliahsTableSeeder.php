<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MataKuliahsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('mata_kuliahs')->delete();

        \DB::table('mata_kuliahs')->insert([
            0 => [
                'id' => 1,
                'kode' => '1DCP95432',
                'nama' => 'PBO',
                'kelas' => 'A',
                'sks' => '3',
                'semester' => '3',
                'created_at' => '2021-09-25 10:02:19',
                'updated_at' => '2021-09-25 10:02:19',
            ],
            1 => [
                'id' => 2,
                'kode' => '1DCP95434',
                'nama' => 'Data Mining',
                'kelas' => 'B',
                'sks' => '3',
                'semester' => '4',
                'created_at' => '2021-09-25 10:02:19',
                'updated_at' => '2021-09-25 10:02:19',
            ],
            2 => [
                'id' => 3,
                'kode' => '1DCP95431',
                'nama' => 'Metode Penelitian',
                'kelas' => 'A',
                'sks' => '3',
                'semester' => '5',
                'created_at' => '2021-09-25 15:03:58',
                'updated_at' => '2021-09-25 15:03:58',
            ],
        ]);
    }
}
