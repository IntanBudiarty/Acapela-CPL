<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skor extends Model
{
    use HasFactory;

    protected $fillable = [
        'rumusan_akhir_mk_id',
        'kode_cpmk',
    ];
}
