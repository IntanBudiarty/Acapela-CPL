<?php

namespace App\Imports;

use App\Models\MataKuliah;
use Maatwebsite\Excel\Concerns\ToModel;

class MataKuliahImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new MataKuliah([
            'kode' => $row [0],
            'nama' => $row[1], 
            'kelas' => ($row[2]),
            'sks' => ($row[3]),
            'semester' => ($row[4]),
            'dosen_pengampu_1' => ($row[5]),
            'dosen_pengampu_2' => ($row[6]),
        ]);       
    }
}
