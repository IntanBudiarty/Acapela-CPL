<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswas';

    protected $fillable = ['nim', 'nama', 'angkatan', 'kelas'];
     public function mataKuliah()
    {
        return $this->belongsToMany(MataKuliah::class, 'mahasiswa_mata_kuliah', 'mahasiswa_id', 'mata_kuliah_id');
    }

    protected $guarded = [
        'id',
    ];

    public function mataKuliahs()
    {
        return $this->belongsToMany(MataKuliah::class, 'mahasiswa_mata_kuliah', 'mahasiswa_id', 'mata_kuliah_id')
            ->withPivot('semester')
            ->withTimestamps();
    }

    public function krs()
    {
        return $this->hasMany(KRS::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function kcpmk()
    {
        return $this->hasMany(Kcpmk::class);
    }

    public function kcpl()
    {
        return $this->hasMany(Kcpl::class);
    }

    public function rumusanAkhirMk()
    {
        return $this->belongsToMany(RumusanAkhirMk::class);
    }
}
