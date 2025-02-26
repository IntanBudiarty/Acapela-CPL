<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DosenAdminsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('dosen_admins')->delete();

        \DB::table('dosen_admins')->insert([
            0 => [
                'id' => 1,
                'user_id' => 1,
                'nip' => '12345',
                'nama' => 'Zhafron Adani Kautsar',
                'created_at' => '2021-10-03 07:43:45',
                'updated_at' => '2021-10-03 07:43:45',
            ],
            1 => [
                'id' => 2,
                'user_id' => 2,
                'nip' => '197601182003122003',
                'nama' => 'Felicia Sylviana, ST., MM',
                'created_at' => '2021-10-03 07:43:45',
                'updated_at' => '2021-10-03 07:43:45',
            ],
        ]);
    }
}
