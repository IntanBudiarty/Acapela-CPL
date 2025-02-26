<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketercapaian extends Model
{
    protected $table = 'ketercapaian'; 
    protected $fillable = [
        'mahasiswa_id', 'mata_kuliah_id', 'nama_mk', 'kd_cpl', 'kd_cpmk', 'skor_maksimal', 'total_skor'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
    public function nilai()
    {
        return $this->belongsTo(Nilai::class);
    }
    public function rumusanAkhirMk()
{
    return $this->belongsTo(RumusanAkhirMk::class, 'rumusan_akhir_mk_id');
}

}
