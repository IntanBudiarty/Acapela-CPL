<?php

namespace App\Imports;

use App\Models\RumusanAkhirMk;
use Maatwebsite\Excel\Concerns\ToModel;

class RumusanAkhirMkImport implements ToModel
{
    public function model(array $row)
    {
        // Lewati baris header
        if ($row[0] === 'mata_kuliah_id') {
            return null;
        }

        return new RumusanAkhirMk([
            'mata_kuliah_id' => $row[0],
            'nama_mk'        => $row[1],
            'kd_cpl'         => $row[2],
            'kd_cpmk'        => $row[3],
            'skor_maksimal'  => $row[4],
            'total_skor'     => $row[5],
        ]);
    }
}
