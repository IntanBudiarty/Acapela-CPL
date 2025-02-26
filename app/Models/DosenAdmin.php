<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenAdmin extends Model
{
    use HasFactory;
    protected $table = 'dosen_admins'; 
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function btp()
    {
        return $this->hasMany(Btp::class);
    }

    public function rolesmk()
    {
        return $this->hasMany(Rolesmk::class, 'dosen_admin_id');
    }
    public function mataKuliahKoordinator()
    {
        return $this->belongsToMany(MataKuliah::class, 'koordinator_mata_kuliah', 'dosen_id', 'mata_kuliah_id');
    }
    public function mataKuliah1()
    {
        return $this->hasMany(MataKuliah::class, 'dosen_pengampu_1');
    }

    public function mataKuliah2()
    {
        return $this->hasMany(MataKuliah::class, 'dosen_pengampu_2');
    }
    public function semuaMataKuliah()
    {
        return $this->mataKuliah1->merge($this->mataKuliah2);
    }
}
