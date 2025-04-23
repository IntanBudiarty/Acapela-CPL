<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilais';

    protected $fillable = [
        'mata_kuliah_id',
        'mahasiswa_id',
        'rumusan_akhir_mk_id',
        'nilai',
        'total',
        'akumulasi',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function rumusanAkhirMk()
    {
        return $this->belongsTo(RumusanAkhirMk::class, 'rumusan_akhir_mk_id');
    }
    public function cpmk()
    {
        return $this->belongsTo(CPMK::class, 'cpmk_id');
    }
    public function ketercapaian()
    {
        return $this->hasOne(Ketercapaian::class, 'mata_kuliah_id');
    }
    public function getAkumulasiAttribute()
    {
        return $this->nilai + $this->total; // Sesuaikan dengan perhitungan akumulasi yang benar
    }


}
