<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function krs()
    {
        return $this->hasMany(KRS::class);
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

    public function kcpl()
    {
        return $this->hasMany(Kcpl::class);
    }

    public function rolesmk()
    {
        return $this->hasMany(Rolesmk::class);
    }
}
