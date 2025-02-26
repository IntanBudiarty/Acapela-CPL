<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use App\Models\RumusanAkhirMk;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        // Ambil semua data nilai dengan relasi
        $nilais = Nilai::with(['mataKuliah', 'mahasiswa', 'rumusanAkhirMk'])->get();
        $mataKuliah = MataKuliah::all();
        $mahasiswaList = Mahasiswa::all();
        $rumusanAkhirMkList = RumusanAkhirMk::all();

        return view('nilai.index', compact('nilais', 'mataKuliah', 'mahasiswaList', 'rumusanAkhirMkList'));
    }

    public function show($mataKuliahId)
    {
        // Ambil data mata kuliah, mahasiswa, dan rumusan terkait
        $mataKuliah = MataKuliah::findOrFail($mataKuliahId);
        $rumusanAkhirMkGrouped = RumusanAkhirMk::where('mata_kuliah_id', $mataKuliahId)
            ->get()
            ->groupBy('kd_cpl');
            $mahasiswaList = Mahasiswa::whereHas('mataKuliah', function ($query) use ($mataKuliahId) {
                $query->where('mata_kuliah_id', $mataKuliahId);
            })->get();
        

        // Ambil nilai mahasiswa terkait
        $nilaiMahasiswa = [];
        $akumulasiMahasiswa = [];
        foreach ($mahasiswaList as $mahasiswa) {
            $nilaiMahasiswa[$mahasiswa->id] = Nilai::where('mahasiswa_id', $mahasiswa->id)
                ->where('mata_kuliah_id', $mataKuliahId)
                ->get()
                ->keyBy('rumusan_akhir_mk_id');
                $totalNilai = $nilaiMahasiswa[$mahasiswa->id]->sum('nilai');
        
                $akumulasi = $nilaiMahasiswa[$mahasiswa->id]->sum('nilai');
                $mahasiswa->akumulasi = $akumulasi; // Simpan akumulasi di objek mahasiswa
                $mahasiswa->grade = $this->getGrade($akumulasi); // Hitung grade berdasarkan akumulasi
            }
        

        return view('nilai.show', compact('mataKuliah', 'rumusanAkhirMkGrouped', 'mahasiswaList', 'nilaiMahasiswa','akumulasiMahasiswa'));
    }

    public function updateNilai(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nilai' => 'required|array',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
        ]);
    
        $mataKuliahId = $request->mata_kuliah_id;
    
        foreach ($request->nilai as $mahasiswaId => $rumusans) {
            $totalNilai = 0;
    
            foreach ($rumusans as $rumusanAkhirMkId => $nilai) {
                Nilai::updateOrCreate(
                    [
                        'mahasiswa_id' => $mahasiswaId,
                        'mata_kuliah_id' => $mataKuliahId,
                        'rumusan_akhir_mk_id' => $rumusanAkhirMkId,
                    ],
                    ['nilai' => $nilai]
                );
    
                $totalNilai += $nilai; // Hitung total nilai
            }
    
            // Update total nilai
            Nilai::where('mahasiswa_id', $mahasiswaId)
                ->where('mata_kuliah_id', $mataKuliahId)
                ->update(['total' => $totalNilai]);
        }
    
        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diperbarui.');
    }     

    private function getGrade($total)
    {
        if ($total >= 85) {
            return 'A';
        } elseif ($total >= 80) {
            return 'A-';
        } elseif ($total >= 75) {
            return 'B+';
        } elseif ($total >= 70) {
            return 'B';
        } elseif ($total >= 65) {
            return 'B-';
        } elseif ($total >= 60) {
            return 'C+';
        } elseif ($total >= 50) {
            return 'C';
        } else {
            return 'D';
        }
    }
}
