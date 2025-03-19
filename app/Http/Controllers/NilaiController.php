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
        $user = auth()->user();
        $angkatanList = Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');

        $selectedAngkatan = $request->angkatan ?? $angkatanList->first();
        if ($user->status == 'Dosen') {
            $mataKuliah = MataKuliah::where('dosen_pengampu_1', $user->id)
                ->orWhere('dosen_pengampu_2', $user->id)
                ->get();
        } else {
            // Jika admin, tampilkan semua mata kuliah
            $mataKuliah = MataKuliah::all();
        }

        return view('nilai.index', compact('mataKuliah', 'angkatanList', 'selectedAngkatan'));
    }


    public function show(Request $request, $mataKuliahId)
    {
        $mataKuliah = MataKuliah::findOrFail($mataKuliahId);

        $angkatanList = Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        $selectedAngkatan = $request->angkatan ?? $angkatanList->first();

        // Filter mahasiswa berdasarkan angkatan
        $mahasiswaList = Mahasiswa::whereHas('mataKuliah', function ($query) use ($mataKuliahId) {
                $query->where('mata_kuliah_id', $mataKuliahId);
            })
            ->where('angkatan', $selectedAngkatan)
            ->get();

        $rumusanAkhirMkGrouped = RumusanAkhirMk::where('mata_kuliah_id', $mataKuliahId)
            ->get()
            ->groupBy('kd_cpl');

        $nilaiMahasiswa = [];
        foreach ($mahasiswaList as $mahasiswa) {
            $nilaiMahasiswa[$mahasiswa->id] = Nilai::where('mahasiswa_id', $mahasiswa->id)
                ->where('mata_kuliah_id', $mataKuliahId)
                ->get()
                ->keyBy('rumusan_akhir_mk_id');
        }

        return view('nilai.show', compact('mataKuliah', 'rumusanAkhirMkGrouped', 'mahasiswaList', 'nilaiMahasiswa', 'angkatanList', 'selectedAngkatan'));
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
