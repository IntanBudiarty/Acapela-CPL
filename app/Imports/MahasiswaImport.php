<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (Mahasiswa::where('nim', $row['nim'])->exists()) {
            return null; 
        }

        return new Mahasiswa([
            'nim'     => $row['nim'],
            'nama'    => $row['nama'],
            'angkatan' => $row['angkatan'],
        ]);
    }
}
