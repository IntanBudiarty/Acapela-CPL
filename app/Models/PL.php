<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PL extends Model
{
    use HasFactory;
    protected $table = 'pl';  // Pastikan nama tabel sesuai
    protected $fillable = ['kode_pl', 'deskripsi'];
}
