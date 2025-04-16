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
    public function index(Request $request)
    {
        $angkatan = $request->input('angkatan');

        $mahasiswaQuery = DB::table('mahasiswas');

        if ($angkatan) {
            $mahasiswaQuery->where('angkatan', $angkatan);
        }

        $mahasiswa = $mahasiswaQuery->get();
        $ketercapaian = Ketercapaian::with('mahasiswa', 'mataKuliah')->get();
        $nilai = Nilai::with('mahasiswa', 'mataKuliah')->get();
        $mataKuliah = MataKuliah::all();
        $listAngkatan = DB::table('mahasiswas')->select('angkatan')->distinct()->orderBy('angkatan', 'desc')->get();

        return view('ketercapaian.index', [
            'nilai' => $nilai,
            'mahasiswa' => $mahasiswa,
            'mataKuliah' => $mataKuliah,
            'ketercapaian' => $ketercapaian,
            'listAngkatan' => $listAngkatan,
            'selectedAngkatan' => $angkatan
        ]);
    }


    public function show($id)
    {
        // Ambil data mahasiswa berdasarkan ID
        $mahasiswa = Mahasiswa::findOrFail($id);

    // Ambil data ketercapaian berdasarkan mahasiswa ID
    $ketercapaianQuery = Nilai::with(['mataKuliah', 'rumusanAkhirMk'])
        ->where('mahasiswa_id', $id);

    // Jika semester dipilih, filter berdasarkan semester dari relasi mata kuliah
    if ($semester) {
        $ketercapaianQuery->whereHas('mataKuliah', function ($query) use ($semester) {
            $query->where('semester', $semester);
        });
    }

    $ketercapaian = $ketercapaianQuery->get()->groupBy('mata_kuliah_id');

    // Hitung rentang nilai berdasarkan total nilai untuk setiap mata kuliah
    $rentangNilaiQuery = Nilai::where('mahasiswa_id', $id);

    if ($semester) {
        $rentangNilaiQuery->whereHas('mataKuliah', function ($query) use ($semester) {
            $query->where('semester', $semester);
        });
    }

    $rentangNilai = $rentangNilaiQuery->get()
        ->groupBy('mata_kuliah_id')
        ->map(function ($nilaiItems) {
            $totalNilai = $nilaiItems->sum('nilai');
            return [
                'total_nilai' => $totalNilai,
                'grade' => $this->getGrade($totalNilai),
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

        // Ambil total skor maksimal per CPL dengan menjumlahkan seluruh skor_maksimal dari CPMK terkait
        $rumusanAkhirCpl = DB::table('rumusan_akhir_cpl')
            ->select('kd_cpl', DB::raw('SUM(skor_maksimal) as total_skor_maksimal'))
            ->groupBy('kd_cpl')
            ->get()
            ->pluck('total_skor_maksimal', 'kd_cpl'); // Ambil total skor maksimal per CPL

        $capaianCpl = [];

        foreach ($nilai as $item) {
            // Ambil daftar CPL dari rumusanAkhirMk
            $kdCplList = explode(',', $item->rumusanAkhirMk->kd_cpl ?? '');
            $totalNilai = $item->nilai; // Nilai CPMK mahasiswa

            foreach ($kdCplList as $kdCpl) {
                $kdCpl = trim($kdCpl); // Bersihkan spasi

                if (!empty($kdCpl)) {
                    // Jika CPL sudah ada, tambahkan nilai
                    if (isset($capaianCpl[$kdCpl])) {
                        $capaianCpl[$kdCpl]['total_nilai'] += $totalNilai;
                    } else {
                        // Jika CPL belum ada, buat entri baru dengan total skor maksimal yang benar
                        $capaianCpl[$kdCpl] = [
                            'kode_cpl' => $kdCpl,
                            'total_nilai' => $totalNilai,
                            'total_skor_maksimal' => $rumusanAkhirCpl[$kdCpl] ?? 0, // Ambil total skor maksimal dari semua CPMK dalam CPL
                            'persentase' => 0,
                        ];
                    }
                }
            }
        }

        // *Hitung persentase ketercapaian berdasarkan total skor maksimal dari rumusanAkhirCpl*
        foreach ($capaianCpl as $kodeCpl => &$cplData) {
            $nilaiMax = $cplData['total_skor_maksimal']; // Gunakan total skor maksimal yang benar

            if ($nilaiMax > 0) {
                // Hitung persentase ketercapaian
                $cplData['persentase'] = number_format(($cplData['total_nilai'] / $nilaiMax) * 100, 2);
            } else {
                $cplData['persentase'] = 0;
            }
        }

        // Urutkan berdasarkan kode CPL (menggunakan pembanding untuk memastikan urutan yang benar)
        ksort($capaianCpl);

        // Format ulang kode CPL untuk memastikan format seperti CPL-01, CPL-02, dll
        $capaianCpl = array_map(function ($cpl) {
            $cpl['kode_cpl'] = 'CPL-' . str_pad(explode('-', $cpl['kode_cpl'])[1], 2, '0', STR_PAD_LEFT);
            return $cpl;
        }, $capaianCpl);

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
        } elseif ($total >= 55) {
            return 'C';
        } elseif ($total >= 45) {
            return 'D';
        } else {
            return 'E';
        }
    }
}
