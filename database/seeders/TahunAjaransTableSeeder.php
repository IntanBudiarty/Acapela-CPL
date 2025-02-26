<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TahunAjaransTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tahun_ajarans')->delete();

        \DB::table('tahun_ajarans')->insert([
            0 => [
                'id' => 1,
                'tahun' => '2020/2021',
                'created_at' => '2021-09-25 10:02:19',
                'updated_at' => '2021-09-25 10:02:19',
            ],
            1 => [
                'id' => 2,
                'tahun' => '2019/2020',
                'created_at' => '2021-09-25 10:02:19',
                'updated_at' => '2021-09-25 10:02:19',
            ],
            2 => [
                'id' => 3,
                'tahun' => '2021/2022',
                'created_at' => '2021-09-25 15:10:37',
                'updated_at' => '2021-09-25 15:10:37',
            ],
        ]);
    }
}
