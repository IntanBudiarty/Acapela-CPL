<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CPLDetail extends Model
{
    use HasFactory;

    protected $table = 'detail_cpls'; // Nama tabel di database

    protected $fillable = [
        'cpl_id',         // Foreign key ke tabel CPL
        'cpmk_id',        // Foreign key ke tabel CPMK
        'nama_cpmk',      // Nama CPMK
        'mata_kuliah_id', // Foreign key ke tabel Mata Kuliah
    ];

    // Relasi ke model CPL (CPL Induk)
    public function cpl()
    {
        return $this->belongsTo(CPL::class, 'cpl_id');
    }

    // Relasi ke model CPMK
    public function cpmk()
    {
        return $this->belongsTo(CPMK::class, 'cpmk_id');
    }

    // Relasi ke model Mata Kuliah
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
}
