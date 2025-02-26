<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NilaisTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('nilais')->delete();

        \DB::table('nilais')->insert([
            0 => [
                'id_nilai' => 'NIL001',
                'kd_mk' => 'MK001',
                'kd_cpmk' => 'CPMK001',
                'kd_cpl' => 'CPL001',
                'npm' => 12345678,
                'nama_mhs' => 'John Doe',
                'nilai_cpmk' => 90,
                'nilai_mk' => 85,
            ],
            1 => [
                'id' => 2,
                'mahasiswa_id' => 2,
                'btp_id' => 2,
                'nilai' => 80.0,
                'created_at' => '2021-09-25 10:02:20',
                'updated_at' => '2021-09-25 10:02:20',
            ],
            2 => [
                'id' => 3,
                'mahasiswa_id' => 1,
                'btp_id' => 3,
                'nilai' => 90.0,
                'created_at' => '2021-09-26 04:46:00',
                'updated_at' => '2021-09-26 04:46:00',
            ],
            3 => [
                'id' => 4,
                'mahasiswa_id' => 1,
                'btp_id' => 4,
                'nilai' => 80.0,
                'created_at' => '2021-09-26 04:46:04',
                'updated_at' => '2021-09-26 04:46:13',
            ],
            4 => [
                'id' => 5,
                'mahasiswa_id' => 1,
                'btp_id' => 5,
                'nilai' => 80.0,
                'created_at' => '2021-09-26 04:46:17',
                'updated_at' => '2021-09-26 04:46:17',
            ],
            5 => [
                'id' => 6,
                'mahasiswa_id' => 1,
                'btp_id' => 6,
                'nilai' => 80.0,
                'created_at' => '2021-09-26 04:46:22',
                'updated_at' => '2021-09-26 04:46:22',
            ],
            6 => [
                'id' => 7,
                'mahasiswa_id' => 1,
                'btp_id' => 7,
                'nilai' => 80.0,
                'created_at' => '2021-09-26 04:46:27',
                'updated_at' => '2021-09-26 04:46:27',
            ],
            7 => [
                'id' => 8,
                'mahasiswa_id' => 1,
                'btp_id' => 8,
                'nilai' => 80.0,
                'created_at' => '2021-09-26 04:46:33',
                'updated_at' => '2021-09-26 04:46:33',
            ],
            8 => [
                'id' => 9,
                'mahasiswa_id' => 1,
                'btp_id' => 9,
                'nilai' => 80.0,
                'created_at' => '2021-09-26 04:46:37',
                'updated_at' => '2021-09-26 04:46:37',
            ],
            9 => [
                'id' => 10,
                'mahasiswa_id' => 1,
                'btp_id' => 10,
                'nilai' => 80.0,
                'created_at' => '2021-09-26 04:46:43',
                'updated_at' => '2021-09-26 04:46:43',
            ],
            10 => [
                'id' => 11,
                'mahasiswa_id' => 1,
                'btp_id' => 11,
                'nilai' => 80.0,
                'created_at' => '2021-09-26 04:46:49',
                'updated_at' => '2021-09-26 04:46:49',
            ],
            11 => [
                'id' => 12,
                'mahasiswa_id' => 1,
                'btp_id' => 12,
                'nilai' => 80.0,
                'created_at' => '2021-09-26 04:46:54',
                'updated_at' => '2021-09-26 04:46:54',
            ],
        ]);
    }
}
