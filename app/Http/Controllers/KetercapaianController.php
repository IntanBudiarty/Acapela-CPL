<?php

namespace App\Http\Controllers;
use App\Models\Ketercapaian;
use Illuminate\Http\Request;
use App\Models\Cpl; 
use App\Models\Mahasiswa;
use App\Models\DosenAdmin;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use Auth;
use Crypt;
// use Illuminate\Http\Request;
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
            'nilai' =>$nilai,
            'mahasiswa'=>$mahasiswa,
            'mataKuliah'=>$mataKuliah,
            'ketercapaian'=>$ketercapaian,
           
        ]);
    }
    public function show($id)
{
    // Ambil data mahasiswa
    $mahasiswa = Mahasiswa::findOrFail($id);
    
    // Ambil data ketercapaian untuk mahasiswa ini
    $ketercapaian = Nilai::with(['mataKuliah', 'rumusanAkhirMk'])
        ->where('mahasiswa_id', $id)
        ->get()
        ->groupBy('mata_kuliah_id');
    

    // Ambil rentang nilai dan total nilai untuk setiap mata kuliah
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


    // Ambil capaian CPL dari tabel cpls dan hitung total nilai berdasarkan CPL
    $capaianCpl = $this->calculateCapaianCpl($id);
    
    // Kirim data ke view
    return view('ketercapaian.show', compact('mahasiswa', 'ketercapaian', 'rentangNilai', 'capaianCpl'));
}

public function calculateCapaianCpl($mahasiswaId)
{
    $nilai = Nilai::with('rumusanAkhirMk')
        ->where('mahasiswa_id', $mahasiswaId)
        ->get();
    
    // Nilai maksimal untuk masing-masing CPL
    $nilaiMaksimal = [
        'CPL01' => 480,
        'CPL02' => 664,
        'CPL03' => 990,
        'CPL04' => 520,
        'CPL05' => 165,
        'CPL06' => 295,
        'CPL07' => 315,
        'CPL08' => 383,
        'CPL09' => 190,
        'CPL10' => 440,
        'CPL11' => 375,
        'CPL12' => 285,
    ];

    $capaianCpl = [];

    foreach ($nilai as $item) {
        // Ambil data CPL dan total nilai dari rumusanAkhirMk
        $kdCplList = explode(',', $item->rumusanAkhirMk->kd_cpl ?? ''); // Antisipasi null
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
                ];
            }
        }
    }

    foreach ($capaianCpl as $kodeCpl => $cplData) {
        // Ambil nama CPL
        $cpl = Cpl::where('kode_cpl', $kodeCpl)->first();
        $capaianCpl[$kodeCpl]['nama_cpl'] = $cpl->nama_cpl ?? 'Nama CPL Tidak Ditemukan';

        // Hitung persentase jika nilai maksimal tersedia
        $nilaiMax = $nilaiMaksimal[$kodeCpl] ?? 0;
        if ($nilaiMax > 0) {
            $capaianCpl[$kodeCpl]['akumulasi'] = number_format(($cplData['total_nilai'] / $nilaiMax) * 100, 2);
        } else {
            $capaianCpl[$kodeCpl]['akumulasi'] = 0;
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