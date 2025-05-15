<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpl extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi dengan RumusanAkhirMk (menggunakan tabel pivot)
    public function rumusanAkhirMk()
    {
        return $this->belongsToMany(RumusanAkhirMk::class, 'mata_kuliah_cpl', 'cpl_id', 'mata_kuliah_id')
                    ->withPivot('kode_cpl'); // Menambahkan kolom kode_cpl dari pivot
    }

    // Relasi dengan CPMK
    public function cpmks()
    {
        return $this->hasMany(Cpmk::class, 'cpl_id', 'id');
    }

    // Relasi many-to-many dengan MataKuliah (menggunakan pivot dengan kolom kode_cpl)
    public function mataKuliah()
    {
        return $this->belongsToMany(MataKuliah::class, 'mata_kuliah_cpl', 'cpl_id', 'mata_kuliah_id')
                    ->withPivot('kode_cpl'); // Menambahkan kolom kode_cpl dari pivot
    }

    public function rumusanAkhirMkCpls()
    {
        return $this->hasMany(RumusanAkhirMkCpl::class, 'cpl_id');
    }
}
