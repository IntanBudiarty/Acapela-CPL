<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RumusanAkhirMkCpl extends Model
{
    use HasFactory;

    protected $fillable = [
        'rumusan_akhir_mk_id',
        'cpl_id' // pastikan field ini ada di table
    ];

    public function rumusanAkhirMk()
    {
        return $this->belongsTo(RumusanAkhirMk::class, 'rumusan_akhir_mk_id');
    }

    public function cpl()
    {
        return $this->belongsTo(Cpl::class, 'cpl_id');
    }
}
