<?php

namespace App\Models;

use App\Models\RumusanAkhirMK;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliahs';

    protected $fillable = [
        'kode', 'nama', 'kelas', 'sks', 'semester', 'dosen_pengampu_1', 'dosen_pengampu_2',
    ];

    public function rumusanAkhirMk()
    {
        return $this->hasMany(RumusanAkhirMk::class, 'mata_kuliah_id');
    }

    public function cpls()
    {
        return $this->belongsToMany(Cpl::class, 'mata_kuliah_cpl', 'mata_kuliah_id', 'cpl_id')
            ->withPivot('kode_cpl');
    }
    public function cpmk()
    {
        return $this->hasMany(Cpmk::class);  // Satu mata kuliah memiliki banyak CPMK
    }

    // Relasi ke CPMK melalui tabel pivot
    public function cpmks()
    {
        return $this->belongsToMany(Cpmk::class, 'mata_kuliah_cpmk', 'mata_kuliah_id', 'cpmk_id')
            ->withPivot('kode_cpmk'); // Menambahkan 'kode_cpmk' dari tabel pivot
    }

    public function kcpmk()
    {
        return $this->hasMany(Kcpmk::class);
    }

    public function kcpl()
    {
        return $this->hasMany(Kcpl::class);
    }

    public function rolesmk()
    {
        return $this->hasMany(Rolesmk::class,  'mata_kuliah_id');
    }
    public function dosenPengampu1()
    {
        return $this->belongsTo(DosenAdmin::class, 'dosen_pengampu_1', 'id');
    }

    public function dosenPengampu2()
    {
        return $this->belongsTo(DosenAdmin::class, 'dosen_pengampu_2', 'id');
    }

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_mata_kuliah', 'mata_kuliah_id', 'mahasiswa_id');
    }
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function koordinator()
    {
        return $this->hasMany(Rolesmk::class, 'mata_kuliah_id');
    }
    public function rumusanAkhirCpl()
    {
        return $this->belongsTo(RumusanAkhirCpl::class);
    }
    public function nilai()
    {
        return $this->hasMany(Nilai::class,  'mahasiswa_id', 'mata_kuliah_id', 'mata_kuliah_nilai');
    }
    public function ketercapaian()
    {
        return $this->hasMany(Ketercapaian::class);
    }

}
