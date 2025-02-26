<?php

namespace App\Imports;

use App\Models\Cpl;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class CplImport implements ToModel
{
     /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Cpl([
            'kode_cpl' => $row[0], // Sesuaikan dengan kolom pada file Excel
            'nama_cpl' => $row[1],  // Sesuaikan dengan kolom pada file Excel
        ]);
    }
}
    
