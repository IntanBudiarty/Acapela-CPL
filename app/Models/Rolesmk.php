<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rolesmk extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_ajaran_id',
        'mata_kuliah_id',
        'dosen_admin_id',
        'semester',
        'status',
    ];

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function mata_kuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen_admin()
    {
        return $this->belongsTo(DosenAdmin::class);
    }
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_admin_id');
    }
}
