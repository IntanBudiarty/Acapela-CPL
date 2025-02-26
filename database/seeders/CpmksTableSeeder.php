<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CpmksTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('cpmks')->delete();

        \DB::table('cpmks')->insert([
            0 => [
                'id' => 1,
                'mata_kuliah_id' => 1,
                'kode_cpmk' => 'CPMK1',
                'nama_cpmk' => 'Menjelaskan Konsep Fisika',
                'created_at' => '2021-09-25 10:02:19',
                'updated_at' => '2021-09-25 10:02:19',
            ],
            1 => [
                'id' => 2,
                'mata_kuliah_id' => 2,
                'kode_cpmk' => 'CPMK1',
                'nama_cpmk' => 'menjelaskan sesuatu',
                'created_at' => '2021-09-25 10:02:19',
                'updated_at' => '2021-09-25 10:02:19',
            ],
            2 => [
                'id' => 3,
                'mata_kuliah_id' => 3,
                'kode_cpmk' => 'CPMK1',
                'nama_cpmk' => 'varius duis at consectetur lorem',
                'created_at' => '2021-09-25 15:08:31',
                'updated_at' => '2021-09-25 15:08:31',
            ],
            3 => [
                'id' => 4,
                'mata_kuliah_id' => 4,
                'kode_cpmk' => 'CPMK2',
                'nama_cpmk' => 'aliquam purus sit amet luctus',
                'created_at' => '2021-09-25 15:08:44',
                'updated_at' => '2021-09-25 15:08:44',
            ],
            4 => [
                'id' => 6,
                'mata_kuliah_id' => 5,
                'kode_cpmk' => 'CPMK3',
                'nama_cpmk' => 'enim diam vulputate ut pharetra',
                'created_at' => '2021-09-25 15:09:12',
                'updated_at' => '2021-09-25 15:12:44',
            ],
            5 => [
                'id' => 7,
                'mata_kuliah_id' => 6,
                'kode_cpmk' => 'CPMK4',
                'nama_cpmk' => 'eget nunc scelerisque viverra mauris',
                'created_at' => '2021-09-25 15:09:25',
                'updated_at' => '2021-09-25 15:12:48',
            ],
            6 => [
                'id' => 8,
                'mata_kuliah_id' => 7,
                'kode_cpmk' => 'CPMK5',
                'nama_cpmk' => 'fermentum dui faucibus in ornare',
                'created_at' => '2021-09-25 15:09:33',
                'updated_at' => '2021-09-25 15:12:55',
            ],
            7 => [
                'id' => 9,
                'mata_kuliah_id' => 8,
                'kode_cpmk' => 'CPMK6',
                'nama_cpmk' => 'lorem dolor sed viverra ipsum',
                'created_at' => '2021-09-25 15:09:41',
                'updated_at' => '2021-09-25 15:13:01',
            ],
            8 => [
                'id' => 10,
                'mata_kuliah_id' => 9,
                'kode_cpmk' => 'CPMK7',
                'nama_cpmk' => 'mi ipsum faucibus vitae aliquet',
                'created_at' => '2021-09-25 15:09:51',
                'updated_at' => '2021-09-25 15:13:08',
            ],
            9 => [
                'id' => 11,
                'mata_kuliah_id' => 10,
                'kode_cpmk' => 'CPMK8',
                'nama_cpmk' => 'mattis vulputate enim nulla aliquet',
                'created_at' => '2021-09-25 15:10:01',
                'updated_at' => '2021-09-25 15:13:16',
            ],
            10 => [
                'id' => 12,
                'mata_kuliah_id' => 11,
                'kode_cpmk' => 'CPMK9',
                'nama_cpmk' => 'consectetur adipiscing elit ut aliquam',
                'created_at' => '2021-09-25 15:10:12',
                'updated_at' => '2021-09-25 15:13:23',
            ],
            11 => [
                'id' => 13,
                'mata_kuliah_id' => 12,
                'kode_cpmk' => 'CPMK10',
                'nama_cpmk' => 'tristique risus nec feugiat in',
                'created_at' => '2021-09-25 15:10:22',
                'updated_at' => '2021-09-25 15:13:30',
            ],
        ]);
    }
}
