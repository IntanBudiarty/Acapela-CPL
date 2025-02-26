<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RumusanAkhirCpl extends Model
{
    use HasFactory;
    protected $table = 'rumusan_akhir_cpl';

    protected $fillable = [
        'kd_cpl', 'mata_kuliah_id', 'nama_mk', 'cpmk', 'skor_maksimal', 'total_skor', 'rumusan_akhir_mk_id',
    ];

    // Relasi ke tabel mata_kuliah
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function rumusanAkhirMk()
    {
        return $this->belongsTo(RumusanAkhirMk::class, 'rumusan_akhir_mk_id');
    }

    public function Cpmk()
    {
        return $this->belongsTo(Cpmk::class, 'cpmk', 'kode_cpmk');
    }

}
