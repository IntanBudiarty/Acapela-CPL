<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\DosenAdmin;
use App\Models\MataKuliah;
use App\Models\Ketercapaian;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

    public function capaianCpl($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $kaprodi = DosenAdmin::where('jabatan', 'kaprodi')->first();

        $capaianCpl = $this->calculateCapaianCpl($id); // panggil method calculateCapaianCpl yang udah kamu bikin tadi

        return view('ketercapaian.capaian-cpl', compact('mahasiswa', 'capaianCpl', 'kaprodi'));
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $ketercapaian = Nilai::with(['mataKuliah', 'rumusanAkhirMk.cpl'])
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
            // $capaianCpl = Nilai::with('rumusanAkhirMk.cpl')
            //     ->where('mahasiswa_id', $id)
            //     ->get()
            //     ->flatMap(function ($item) {
            //         return $item->rumusanAkhirMk->cpl->map(function ($cpl) use ($item) {
            //             return [
            //                 'kode_cpl' => $cpl->kode_cpl,
            //                 'nama_cpl' => $cpl->nama_cpl,
            //                 'nilai' => $item->nilai,
            //                 'skor_maksimal' => $item->skor_maksimal
            //             ];
            //         });
            //         return collect();
            //     })
            //     ->groupBy('kode_cpl')
            //     ->map(function ($items, $kodeCpl) {
            //         $namaCpl = collect($items)->first()['nama_cpl'] ?? 'Tidak Ada';
            //         $totalNilai = collect($items)->sum('nilai');
            //         $totalSkorMaksimal = collect($items)->sum('skor_maksimal');
            
            //         return [
            //             'kode_cpl' => $kodeCpl,
            //             'nama_cpl' => $namaCpl,
            //             'total_nilai' => $totalNilai,
            //             'total_skor_maksimal' => $totalSkorMaksimal,
            //             'persentase' => $totalSkorMaksimal > 0 
            //                 ? round(($totalNilai / $totalSkorMaksimal) * 100, 2)
            //                 : 0
            //         ];
            //     })
            //     ->values(); 
        


        // Hitung capaian CPL
        $capaianCpl = $this->calculateCapaianCpl($id);

        $semester = request('semester');

        $nilaiQuery = Nilai::with(['mataKuliah', 'rumusanAkhirMk'])
            ->where('mahasiswa_id', $id);

        if ($semester) {
            $nilaiQuery->whereHas('mataKuliah', function ($q) use ($semester) {
                $q->where('semester', $semester);
            });
        }

        $nilai = $nilaiQuery->get();

        // Filter data menjadi per mata kuliah
        $ketercapaian = $nilai->groupBy('mata_kuliah_id');

        // Hitung capaian CPL
        // $capaianCpl = $this->hitungCapaianCpl($nilai); // <- sesuaikan ini jika perlu

        $semesters = MataKuliah::distinct()->pluck('semester');

        // Kirim data ke view
        return view('ketercapaian.show', compact('mahasiswa', 'ketercapaian', 'rentangNilai', 'capaianCpl', 'semesters'));
    }

    public function calculateCapaianCpl($mahasiswaId)
    {
        $nilai = Nilai::with('rumusanAkhirMk')
            ->where('mahasiswa_id', $mahasiswaId)
            ->get();

        $rumusanAkhirCpl = DB::table('rumusan_akhir_cpl')
            ->select('kd_cpl', DB::raw('SUM(skor_maksimal) as total_skor_maksimal'))
            ->groupBy('kd_cpl')
            ->get()
            ->pluck('total_skor_maksimal', 'kd_cpl');

        // Ambil kode_cpl dan nama_cpl sinkron
        $namaCpl = Cpl::pluck('nama_cpl', 'kode_cpl')->toArray(); // Ini array kode_cpl => nama_cpl

        $capaianCpl = [];

        foreach ($nilai as $item) {
            $kdCplList = explode(',', $item->rumusanAkhirMk->kd_cpl ?? '');
            $totalNilai = $item->nilai;

            foreach ($kdCplList as $kdCpl) {
                $kdCpl = trim($kdCpl);

                if (!empty($kdCpl)) {
                    // Format kode CPL harus sama
                    $kodeCplFormatted = 'CPL-' . str_pad($kdCpl, 2, '0', STR_PAD_LEFT);

                    if (isset($capaianCpl[$kodeCplFormatted])) {
                        $capaianCpl[$kodeCplFormatted]['total_nilai'] += $totalNilai;
                    } else {
                        $capaianCpl[$kodeCplFormatted] = [
                            'kode_cpl' => $kodeCplFormatted,
                            'nama_cpl' => $namaCpl[$kodeCplFormatted] ?? 'Tidak Ada',
                            'total_nilai' => $totalNilai,
                            'total_skor_maksimal' => $rumusanAkhirCpl[$kdCpl] ?? 0,
                            'persentase' => 0,
                            'predikat' => '-'
                        ];
                    }
                }
            }
        }

        // Hitung persentase dan predikat
        foreach ($capaianCpl as &$cplData) {
        $nilaiMax = $cplData['total_skor_maksimal'];

        if ($nilaiMax > 0) {
            $persentase = ($cplData['total_nilai'] / $nilaiMax) * 100;
            $cplData['persentase'] = number_format($persentase, 2);

                if ($persentase >= 85) {
                $cplData['predikat'] = 'Sangat Kompeten (Exemplary)';
            } elseif ($persentase >= 75) {
                $cplData['predikat'] = 'Kompeten (Competent)';
            } elseif ($persentase >= 60) {
                $cplData['predikat'] = 'Berkembang (Developing)';
            } else {
                $cplData['predikat'] = 'Tidak Memuaskan (Unsatisfactory)';
            }
        } else {
            $cplData['persentase'] = 0;
            $cplData['predikat'] = 'Tidak Memuaskan (Unsatisfactory)';
        }
    }

    ksort($capaianCpl);

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

