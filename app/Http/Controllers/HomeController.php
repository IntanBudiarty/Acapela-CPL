<?php

namespace App\Http\Controllers;

use App\Models\DosenAdmin;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;

class HomeController extends Controller
{
    public function index()
    {
        $dosen = count(DosenAdmin::get());
        $mahasiswa = count(Mahasiswa::get());
        $matakuliah = count(MataKuliah::get());

        return view('home', [
            'dosen' => $dosen,
            'mahasiswa' => $mahasiswa,
            'matakuliah' => $matakuliah,
        ]);
    }
}
