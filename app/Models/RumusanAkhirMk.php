<?php

namespace App\Models;

use App\Models\MataKuliah;
use App\Models\Cpl;
use App\Models\Cpmk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RumusanAkhirMk extends Model
{
    use HasFactory;

    protected $table = 'rumusan_akhir_mk';
    protected $fillable = [
        'mata_kuliah_id',
        'nama_mk',
        'kd_cpl',
        'kd_cpmk',
        'skor_maksimal',
        'total_skor'
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }


    public function cpl()
    {
        return $this->belongsToMany(Cpl::class, 'rumusan_akhir_cpl', 'rumusan_akhir_mk_id', 'kd_cpl')
            ->withPivot('kd_cpl');
    }

    public function cpmk()
    {
        return $this->belongsToMany(Cpmk::class, 'mata_kuliah_cpmk', 'mata_kuliah_id', 'cpmk_id')
            ->withPivot('kode_cpmk'); // Pastikan pivot kolom ini termasuk
    }
    public function rumusanAkhirCpl()
    {
        return $this->hasMany(RumusanAkhirCpl::class, 'rumusan_akhir_mk_id');
    }
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'rumusan_akhir_mk_id');
    }
}
