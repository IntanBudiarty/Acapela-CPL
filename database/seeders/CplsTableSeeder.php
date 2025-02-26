<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CplsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('cpls')->delete();

        \DB::table('cpls')->insert([
            0 => [
                'id' => 1,
                'kode_cpl' => 'KU1',
                'nama_cpl' => 'Memiliki kemampuan untuk memecahkan masalah san menemukan solusi yang tepat',
                'created_at' => '2021-09-25 10:02:19',
                'updated_at' => '2021-09-25 10:02:19',
            ],
            1 => [
                'id' => 2,
                'kode_cpl' => 'S1',
                'nama_cpl' => 'Menginternalisasi sikap sesuai Pancasila',
                'created_at' => '2021-09-25 10:02:19',
                'updated_at' => '2021-09-25 10:02:19',
            ],
            2 => [
                'id' => 3,
                'kode_cpl' => 'S2',
                'nama_cpl' => 'Lorem ipsum dolor sit amet, consectetur.',
                'created_at' => '2021-09-25 15:05:41',
                'updated_at' => '2021-09-25 15:05:41',
            ],
            3 => [
                'id' => 4,
                'kode_cpl' => 'P1',
                'nama_cpl' => 'Lorem ipsum dolor sit.',
                'created_at' => '2021-09-25 15:06:00',
                'updated_at' => '2021-09-25 15:06:00',
            ],
            4 => [
                'id' => 5,
                'kode_cpl' => 'P2',
                'nama_cpl' => 'Lorem ipsum dolor sit amet.',
                'created_at' => '2021-09-25 15:06:16',
                'updated_at' => '2021-09-25 15:06:16',
            ],
            5 => [
                'id' => 6,
                'kode_cpl' => 'KU2',
                'nama_cpl' => 'nisl nunc mi ipsum',
                'created_at' => '2021-09-25 15:07:05',
                'updated_at' => '2021-09-25 15:07:05',
            ],
            6 => [
                'id' => 7,
                'kode_cpl' => 'KK1',
                'nama_cpl' => 'semper viverra nam libero justo',
                'created_at' => '2021-09-25 15:07:28',
                'updated_at' => '2021-09-25 15:07:28',
            ],
            7 => [
                'id' => 8,
                'kode_cpl' => 'KK2',
                'nama_cpl' => 'nullam vehicula ipsum a arcu cursus',
                'created_at' => '2021-09-25 15:07:42',
                'updated_at' => '2021-09-25 15:07:42',
            ],
            8 => [
                'id' => 9,
                'kode_cpl' => 'KK3',
                'nama_cpl' => 'varius duis at consectetur lorem',
                'created_at' => '2021-09-25 15:07:55',
                'updated_at' => '2021-09-25 15:07:55',
            ],
        ]);
    }
}
