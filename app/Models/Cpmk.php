<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpmk extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function rumusanAkhirMk()
    {
        // Relasi satu ke banyak ke RumusanAkhirMk
        return $this->hasMany(RumusanAkhirMk::class, 'kd_cpmk', 'kode_cpmk');
    }
    public function mataKuliah()
    {
        return $this->belongsToMany(MataKuliah::class, 'cpmk_mata_kuliah', 'cpmk_id', 'mata_kuliah_id');
    }
    public function btp()
    {
        return $this->hasMany(Btp::class);
    }

    public function bobotcpl()
    {
        return $this->hasMany(Bobotcpl::class);
    }

    public function kcpmk()
    {
        return $this->hasMany(Kcpmk::class);
    }
    public function cpl()
    {
        return $this->belongsTo(Cpl::class, 'cpl_id');

    }
}
