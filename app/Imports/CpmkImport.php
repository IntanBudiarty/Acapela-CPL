<?php

namespace App\Imports;

use App\Models\Cpmk;
use Maatwebsite\Excel\Concerns\ToModel;

class CpmkImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Cpmk([
           'Kode CPL'     => $row[0],
           'kode CPMK' => ($row[1]),
           'Nama CPMK' => ($row[2]),
           'Kode MK' => ($row[3]),
        ]);
    }
}
