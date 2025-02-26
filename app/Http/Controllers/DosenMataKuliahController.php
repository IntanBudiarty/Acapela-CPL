<?php

namespace App\Http\Controllers;
use App\Models\DosenAdmin;
use Illuminate\Http\Request;

class DosenMataKuliahController extends Controller
{
    public function index($dosenId)
    {
        $dosenId = auth()->user()->id; // Sesuaikan mekanisme identifikasi dosen
        $mataKuliah = MataKuliah::where('dosen_pengampu_1', $dosenId)
            ->orWhere('dosen_pengampu_2', $dosenId)
            ->get();
        

        // Ambil mata kuliah yang diampu
        $mataKuliah1 = $dosen->mataKuliah1;
        $mataKuliah2 = $dosen->mataKuliah2;

        // Gabungkan kedua hasil (opsional jika semua mata kuliah perlu digabung)
        $semuaMataKuliah = $mataKuliah1->merge($mataKuliah2);

        return view('dosen.mata_kuliah', compact('dosen', 'semuaMataKuliah'));
    }
}
