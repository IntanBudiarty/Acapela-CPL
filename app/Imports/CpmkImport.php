<?php

namespace App\Imports;

use App\Models\Cpmk;
use App\Models\Cpl;
use App\Models\MataKuliah;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CpmkImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Temukan atau buat CPL berdasarkan kode yang diberikan
        $cpl = Cpl::firstOrCreate(['kode_cpl' => $row['kode_cpl']]);

        // Simpan data CPMK
        $cpmk = Cpmk::create([
            'cpl_id'    => $cpl->id, // Hubungkan dengan CPL
            'kode_cpmk' => $row['kode_cpmk'],
            'nama_cpmk' => $row['nama_cpmk'],
        ]);

        // Proses relasi dengan Mata Kuliah
        $kodeMks = explode(',', $row['kode_mk']); // Pisahkan kode MK jika lebih dari satu
        foreach ($kodeMks as $kodeMk) {
            $mataKuliah = MataKuliah::firstOrCreate(['kode' => trim($kodeMk)]);
            $cpmk->mataKuliah()->attach($mataKuliah->id); // Tambahkan ke relasi pivot
        }

        return $cpmk;
    }
}
