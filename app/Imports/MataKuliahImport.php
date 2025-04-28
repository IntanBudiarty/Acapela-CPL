<?php

namespace App\Imports;

use App\Models\MataKuliah;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Import interface ini

class MataKuliahImport implements ToModel, WithHeadingRow // Implementasikan WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (MataKuliah::where('kode', $row['kode'])->exists()) {
            return null; 
        }
  
        return new MataKuliah([
            'kode' => $row['kode'],  
            'nama' => $row['nama'],  
            'kelas' => $row['kelas'],  
            'sks' => $row['sks'],  
            'semester' => $row['semester'],  
            'dosen_pengampu_1' => $row['dosen_pengampu_1'],  
            'dosen_pengampu_2' => $row['dosen_pengampu_2'],  
        ]);
    }
}
