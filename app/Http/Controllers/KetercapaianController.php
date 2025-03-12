<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ketercapaian;
use App\Models\Cpl; 
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use Illuminate\Support\Facades\DB;

class KetercapaianController extends Controller
{
    public function index()
    {
        $ketercapaian = Ketercapaian::with('mahasiswa', 'mataKuliah')->get();
        $nilai = Nilai::with('mahasiswa', 'mataKuliah')->get();
        $mataKuliah = MataKuliah::all();
        $mahasiswa = DB::table('mahasiswas')->get();

        return view('ketercapaian.index', [
            'nilai' => $nilai,
            'mahasiswa' => $mahasiswa,
            'mataKuliah' => $mataKuliah,
            'ketercapaian' => $ketercapaian,
        ]);
    }

    public function show($id)
    {
        // Ambil data mahasiswa berdasarkan ID
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        // Ambil data ketercapaian berdasarkan mahasiswa ID
        $ketercapaian = Nilai::with(['mataKuliah', 'rumusanAkhirMk'])
            ->where('mahasiswa_id', $id)
            ->get()
            ->groupBy('mata_kuliah_id');
        
        // Hitung rentang nilai berdasarkan total nilai untuk setiap mata kuliah
        $rentangNilai = Nilai::where('mahasiswa_id', $id)
            ->get()
            ->groupBy('mata_kuliah_id')
            ->map(function ($nilaiItems) {
                $totalNilai = $nilaiItems->sum('nilai');  // Jumlahkan nilai untuk setiap mata kuliah
                return [
                    'total_nilai' => $totalNilai,
                    'grade' => $this->getGrade($totalNilai), // Tentukan grade berdasarkan total nilai
                ];
            });

        // Hitung capaian CPL
        $capaianCpl = $this->calculateCapaianCpl($id);
        
        // Kirim data ke view
        return view('ketercapaian.show', compact('mahasiswa', 'ketercapaian', 'rentangNilai', 'capaianCpl'));
    }

    public function calculateCapaianCpl($mahasiswaId)
    {
        // Ambil data nilai mahasiswa
        $nilai = Nilai::with('rumusanAkhirMk')
            ->where('mahasiswa_id', $mahasiswaId)
            ->get();
        
        // Ambil total skor maksimal dari tabel rumusan_akhir_cpl
        $nilaiMaksimal = DB::table('rumusan_akhir_cpl')
            ->pluck('total_skor', 'kd_cpl'); // Ambil total_skor berdasarkan kd_cpl

        $capaianCpl = [];

        foreach ($nilai as $item) {
            // Ambil daftar CPL dari rumusanAkhirMk
            $kdCplList = explode(',', $item->rumusanAkhirMk->kd_cpl ?? ''); // Antisipasi jika null
            $totalNilai = $item->nilai; // Nilai CPMK

            foreach ($kdCplList as $kdCpl) {
                // Jika CPL sudah ada, tambahkan nilai ke totalnya
                if (isset($capaianCpl[$kdCpl])) {
                    $capaianCpl[$kdCpl]['total_nilai'] += $totalNilai;
                } else {
                    // Jika CPL belum ada, buat entri baru
                    $capaianCpl[$kdCpl] = [
                        'kode_cpl' => $kdCpl,
                        'total_nilai' => $totalNilai,
                        'akumulasi' => 0, // Placeholder untuk akumulasi
                        'persentase' => 0, // Placeholder untuk persentase
                    ];
                }
            }
        }

        foreach ($capaianCpl as $kodeCpl => $cplData) {
            // Ambil nama CPL dari tabel cpl
            $cpl = Cpl::where('kode_cpl', $kodeCpl)->first();
            $capaianCpl[$kodeCpl]['nama_cpl'] = $cpl->nama_cpl ?? 'Nama CPL Tidak Ditemukan';

            // Ambil nilai maksimal dari tabel rumusan_akhir_cpl
            $nilaiMax = $nilaiMaksimal[$kodeCpl] ?? 0;
            if ($nilaiMax > 0) {
                // Hitung persentase ketercapaian
                $capaianCpl[$kodeCpl]['persentase'] = number_format(($cplData['total_nilai'] / $nilaiMax) * 100, 2);
            } else {
                $capaianCpl[$kodeCpl]['persentase'] = 0;
            }
        }

        return $capaianCpl;
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
