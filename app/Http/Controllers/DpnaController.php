<?php

namespace App\Http\Controllers;

use App\Models\Btp;
use App\Models\DosenAdmin;
use App\Models\KRS;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Rolesmk;
use App\Models\TahunAjaran;
use Auth;
use Carbon\Carbon;
use Crypt;
use DB;
use Illuminate\Http\Request;
use PDF;

class DpnaController extends Controller
{
    public function index()
    {
        $judul = 'Kelola DPNA';
        $parent = 'DPNA';
        $judulform = 'Cari Data DPNA';

        $ta = TahunAjaran::orderBy('tahun')->get();
        $mk = MataKuliah::orderBy('nama')->get();
        $mhs = Mahasiswa::orderBy('nim')->get();

        return view('dpna.index', [
            'ta' => $ta,
            'mk' => $mk,
            'mhs' => $mhs,
            'judul' => $judul,
            'judulform' => $judulform,
            'parent' => $parent,
        ]);
    }

    public function cari(Request $request)
    {
        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        // Nilai tetap
        $judul = 'Kelola DPNA';
        $parent = 'DPNA';
        $subparent = 'Cari';
        $judulform = 'Cari Data DPNA';

        $ta = TahunAjaran::orderBy('tahun')->get();
        $mk = MataKuliah::orderBy('nama')->get();
        $mhs = Mahasiswa::orderBy('nim')->get();

        $id_ta = Crypt::decrypt($request->tahunajaran);
        $id_sem = Crypt::decrypt($request->semester);
        $id_mk = Crypt::decrypt($request->mk);
        $id_mhs = Crypt::decrypt($request->mhs);

        $id_user = Auth::user()->id;
        $cekstatus = Auth::user()->status;
        $id_dosen = DosenAdmin::with('user')->firstWhere('id', $id_user)->id;
        $getDosen = Rolesmk::with('dosen_admin')
            ->whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND dosen_admin_id = '$id_dosen' AND status = 'koordinator'")
            ->first();
        $getDosenPengampu = Btp::whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND dosen_admin_id = '$id_dosen'")
            ->first();
        if (isset($getDosen) || isset($getDosenPengampu) || $cekstatus === 'Admin') {
            $getnilaitugas = Nilai::whereHas('btp', function ($q) use ($id_sem, $id_mk, $id_ta) {
                $q->whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '1'");
            })->get();
            $getnilaiuts = Nilai::whereHas('btp', function ($q) use ($id_sem, $id_mk, $id_ta) {
                $q->whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '2'");
            })->get();
            $getnilaiuas = Nilai::whereHas('btp', function ($q) use ($id_sem, $id_mk, $id_ta) {
                $q->whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '3'");
            })->get();
            $getbobottugas = Btp::whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '1'")->select(DB::raw('SUM(bobot) jumlah_bobot'))->groupBy('kategori')->value('jumlah_bobot');
            $getbobotuts = Btp::whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '2'")->select(DB::raw('SUM(bobot) jumlah_bobot'))->groupBy('kategori')->value('jumlah_bobot');
            $getbobotuas = Btp::whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '3'")->select(DB::raw('SUM(bobot) jumlah_bobot'))->groupBy('kategori')->value('jumlah_bobot');
            if ($id_mhs === 'semua') {
                $getMhs = KRS::with('mahasiswa')->whereRaw(
                    "tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem'"
                )->get();

                return view('dpna.cari', [
                    'getmhs' => $getMhs,
                    'getnilaiuts' => $getnilaiuts,
                    'getnilaitugas' => $getnilaitugas,
                    'getnilaiuas' => $getnilaiuas,
                    'getbobottugas' => $getbobottugas,
                    'getbobotuts' => $getbobotuts,
                    'getbobotuas' => $getbobotuas,
                    'ta' => $ta,
                    'mk' => $mk,
                    'mhs' => $mhs,
                    'judul' => $judul,
                    'judulform' => $judulform,
                    'parent' => $parent,
                    'subparent' => $subparent,
                ]);
            }
            $getMhs = KRS::with('mahasiswa')->whereRaw(
                "tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND mahasiswa_id = '$id_mhs'"
            )->groupBy('mahasiswa_id')->get();

            return view('dpna.cari', [
                'getmhs' => $getMhs,
                'getnilaiuts' => $getnilaiuts,
                'getnilaitugas' => $getnilaitugas,
                'getnilaiuas' => $getnilaiuas,
                'getbobottugas' => $getbobottugas,
                'getbobotuts' => $getbobotuts,
                'getbobotuas' => $getbobotuas,
                'ta' => $ta,
                'mk' => $mk,
                'mhs' => $mhs,
                'judul' => $judul,
                'judulform' => $judulform,
                'parent' => $parent,
                'subparent' => $subparent,
            ]);
        }

        return redirect()->route('dpna')->with('error', 'Maaf anda bukan dosen koordinator!');
    }

    public function downloadPDF(Request $request)
    {
        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        Carbon::now()->formatLocalized('%A, %d %B %Y');
        // GET
        $id_ta = Crypt::decrypt($request->tahun_ajaran);
        $id_sem = Crypt::decrypt($request->semester);
        $id_mk = Crypt::decrypt($request->mk);

        // Variabel
        $mata_kuliah = MataKuliah::whereId($id_mk)->first();
        $getMhs = KRS::with('mahasiswa')->whereRaw(
            "tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem'"
        )->get();
        $jumlah_mhs = $getMhs->count();
        $jumlah_sks = MataKuliah::whereId($id_mk)->value('sks');
        $semester = MataKuliah::whereId($id_mk)->value('semester');
        $dosen = DosenAdmin::whereHas('btp', function ($query) use ($id_mk, $id_sem, $id_ta) {
            return $query->whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem'");
        })->get();

        $dosenkoor = Rolesmk::with('dosen_admin')
            ->whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND status = 'koordinator'")
            ->first();

        $getnilaitugas = Nilai::whereHas('btp', function ($q) use ($id_sem, $id_mk, $id_ta) {
            $q->whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '1'");
        })->get();
        $getnilaiuts = Nilai::whereHas('btp', function ($q) use ($id_sem, $id_mk, $id_ta) {
            $q->whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '2'");
        })->get();
        $getnilaiuas = Nilai::whereHas('btp', function ($q) use ($id_sem, $id_mk, $id_ta) {
            $q->whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '3'");
        })->get();
        $getbobottugas = Btp::whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '1'")->select(DB::raw('SUM(bobot) jumlah_bobot'))->groupBy('kategori')->value('jumlah_bobot');
        $getbobotuts = Btp::whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '2'")->select(DB::raw('SUM(bobot) jumlah_bobot'))->groupBy('kategori')->value('jumlah_bobot');
        $getbobotuas = Btp::whereRaw("tahun_ajaran_id = '$id_ta' AND mata_kuliah_id = '$id_mk' AND semester = '$id_sem' AND kategori = '3'")->select(DB::raw('SUM(bobot) jumlah_bobot'))->groupBy('kategori')->value('jumlah_bobot');
        $rekap = [];
        function hitung($getnilai, $bobot, $list): float
        {
            $mhs = $getnilai->where('mahasiswa_id', $list);
            $nilai = [];
            foreach ($mhs as $lii) {
                $nilai[] = $lii->nilai * $lii->btp->bobot;
            }

            return round((array_sum($nilai) / $bobot), 2);
        }

        function mutunilai($nilaiakhir)
        {
            if ($nilaiakhir >= 80 && $nilaiakhir <= 100) {
                return 'A';
            }
            if ($nilaiakhir >= 75 && $nilaiakhir < 80) {
                return 'B+';
            }
            if ($nilaiakhir >= 70 && $nilaiakhir < 75) {
                return 'B';
            }
            if ($nilaiakhir >= 65 && $nilaiakhir < 70) {
                return 'C+';
            }
            if ($nilaiakhir >= 60 && $nilaiakhir < 65) {
                return 'C';
            }
            if ($nilaiakhir >= 40 && $nilaiakhir < 60) {
                return 'D';
            }
            if ($nilaiakhir < 40) {
                return 'E';
            }
        }

        function jumlahmutu($rekap, $mutu)
        {
            return $rekap[$mutu] ?? '0';
        }

        function persentase($rekap, $mutu, $jumlah_mhs)
        {
            if (isset($rekap[$mutu])) {
                return ($rekap[$mutu] / $jumlah_mhs) * 100;
            }

            return '0';
        }

        function kelulusan($nilaiakhir)
        {
            if ($nilaiakhir >= 60 && $nilaiakhir <= 100) {
                return 'L';
            }
            if ($nilaiakhir < 60) {
                return 'TL';
            }
        }

        // TCPDF

        //Header
        PDF::setHeaderCallback(function ($pdf) {
            $pdf->Image(asset('media/photos/UPR.jpg'), 10, 10, 25, 25);
            $pdf->SetFont('arialbd', '', 14);
            $pdf->Ln(10);
            $pdf->MultiCell(210, 4, 'KEMENTERIAN PENDIDIKAN, KEBUDAYAAN,', 0, 'C');
            $pdf->MultiCell(210, 4, 'RISET, DAN TEKNOLOGI ', 0, 'C');
            $pdf->SetFont('arialbd', '', 16);
            $pdf->MultiCell(210, 4, 'UNIVERSITAS PALANGKA RAYA', 0, 'C');
            $pdf->MultiCell(210, 4, 'FAKULTAS TEKNIK', 0, 'C');
            $pdf->Ln(1);
            $pdf->SetFont('arialbd', '', 7);
            $pdf->MultiCell(210, 3, 'Alamat : Kampus UPR Tunjung Nyaho Jalan Yos Sudarso Kotak Pos 2/PLKUP Palangka Raya 73112 Kalimantan Tengah - INDONESIA', 0, 'C');
            $pdf->MultiCell(210, 3, 'Telepon/Fax: +62 536-3226487 ; laman: www.upr.ac.id E-Mail: fakultas_teknik@eng.upr.ac.id', 0, 'C');
            $pdf->Line(10, 45, 200, 45);
        });

        // Footer
        PDF::setFooterCallback(function ($pdf) {
            // Position at 15 mm from bottom
            $pdf->SetY(-15);
            // Set font
            $pdf->SetFont('ariali', '', 8);
            // Page number
            $pdf->Cell(0, 10, 'Halaman '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });

        // Isi
        PDF::Addpage('P', 'A4');
        PDF::SetY(50);
        PDF::SetFont('arialbd', '', 12);
        PDF::MultiCell(210, 8, 'DAFTAR HADIR UJIAN DAN ISIAN NILAI', 0, 'C');

        PDF::SetFont('arialbd', '', 7);
        PDF::SetY(60);
        PDF::Cell(28, 5, 'Mata Kuliah ', 0, 0, 'L');
        PDF::Cell(3, 5, ':', 0, 0, 'L');
        PDF::SetFont('arialbd', '', 7);
        PDF::Cell(172, 5, ''.(strtoupper($mata_kuliah->nama)).' ('.(strtoupper($mata_kuliah->kode)).')', 0, 1, 'L');

        PDF::SetFont('arialbd', '', 7);
        PDF::Cell(28, 5, 'Jumlah SKS ', 0, 0, 'L');
        PDF::Cell(3, 5, ':', 0, 0, 'L');
        PDF::Cell(172, 5, $jumlah_sks, 0, 1, 'L');

        PDF::Cell(28, 5, 'Ruang/Kelas ', 0, 0, 'L');
        PDF::Cell(3, 5, ':', 0, 0, 'L');
        PDF::Cell(172, 5, $mata_kuliah->kelas, 0, 1, 'L');

        PDF::Cell(28, 5, 'Jumlah Mahasiswa ', 0, 0, 'L');
        PDF::Cell(3, 5, ':', 0, 0, 'L');
        PDF::Cell(172, 5, $jumlah_mhs, 0, 1, 'L');

        PDF::Cell(28, 5, 'Dosen ', 0, 0, 'L');
        PDF::Cell(3, 5, ':', 0, 0, 'L');
        PDF::SetX(41);
        PDF::Cell(3, 5, ''.'1'.'.', 0, 0, 'L');
        if (isset($dosenkoor)) {
            PDF::Cell(172, 5, ''.($dosenkoor->dosen_admin->nama).' ('.($dosenkoor->dosen_admin->nip).')', 0, 1, 'L');
        } else {
            return redirect()->back()->with('error', 'Dosen koor tidak ditemukan!');
        }
        $no = 2;
        foreach ($dosen as $li => $value) {
            if ($value->nama === $dosenkoor->dosen_admin->nama) {
                continue;
            }
            if ($no > 4) {
                PDF::SetY(70);
                PDF::SetX(160);
            } else {
                PDF::SetX(41);
            }
            PDF::Cell(3, 5, ''.$no.'.', 0, 0, 'L');
            PDF::Cell(172, 5, ''.($value->nama).' ('.($value->nip).')', 0, 1, 'L');
            $no++;
        }
        PDF::SetY(60);
        PDF::SetX(145);
        PDF::Cell(25, 5, 'Jurusan ', 0, 0, 'L');
        PDF::Cell(3, 5, ':', 0, 0, 'L');
        PDF::Cell(172, 5, 'TEKNIK INFORMATIKA', 0, 1, 'L');

        PDF::SetX(145);
        PDF::Cell(25, 5, 'Jenjang ', 0, 0, 'L');
        PDF::Cell(3, 5, ':', 0, 0, 'L');
        PDF::Cell(172, 5, 'S1 TEKNIK INFORMATIKA', 0, 1, 'L');

        PDF::SetX(145);
        PDF::Cell(25, 5, 'Semester ', 0, 0, 'L');
        PDF::Cell(3, 5, ':', 0, 0, 'L');
        PDF::Cell(172, 5, $semester, 0, 1, 'L');

        PDF::SetY(105);
        PDF::SetX(10);
        PDF::SetFont('arialbd', '', 7);
        PDF::Cell(10, 12, 'No', 1, 0, 'C');
        PDF::Cell(20, 12, 'NIM', 1, 0, 'C');
        PDF::Cell(50, 12, 'NAMA MAHASISWA', 1, 0, 'C');
        PDF::Cell(15, 12, 'Paraf', 1, 0, 'C');
        PDF::Cell(18, 6, 'Tugas/PR', 1, 0, 'C');
        PDF::Cell(18, 6, 'Mid Test', 1, 0, 'C');
        PDF::Cell(18, 6, 'Final Test', 1, 0, 'C');
        PDF::Cell(20, 6, 'Nilai Akhir', 1, 0, 'C');
        PDF::Cell(18, 12, 'Keterangan', 1, 0, 'C');
        PDF::SetY(111);
        PDF::SetX(105);
        PDF::Cell(18, 6, '20%', 1, 0, 'C');
        PDF::Cell(18, 6, '30%', 1, 0, 'C');
        PDF::Cell(18, 6, '50%', 1, 0, 'C');
        PDF::Cell(10, 6, 'Angka', 1, 0, 'C');
        PDF::Cell(10, 6, 'Mutu', 1, 0, 'C');
        PDF::SetY(117);
        PDF::SetX(105);
        PDF::SetFont('arial', '', 7);
        foreach ($getMhs as $li => $value) {
            $hitungtugas = hitung($getnilaitugas, $getbobottugas, $value->mahasiswa->id);
            $hitunguts = hitung($getnilaiuts, $getbobotuts, $value->mahasiswa->id);
            $hitunguas = hitung($getnilaiuas, $getbobotuas, $value->mahasiswa->id);
            $hitungnilaiakhir = round(((hitung($getnilaitugas, $getbobottugas, $value->mahasiswa->id) * ($getbobottugas / 100)) + (hitung($getnilaiuts, $getbobotuts, $value->mahasiswa->id) * ($getbobotuts / 100)) + (hitung($getnilaiuas, $getbobotuas, $value->mahasiswa->id) * ($getbobotuas / 100))), 2);
            $rekap[] = mutunilai($hitungnilaiakhir);
            PDF::SetX(10);
            PDF::Cell(10, 7, $li + 1, 1, 0, 'C');
            PDF::Cell(20, 7, $value->mahasiswa->nim, 1, 0, 'C');
            PDF::Cell(50, 7, strtoupper($value->mahasiswa->nama), 1, 0, 'C');
            PDF::Cell(15, 7, '', 1, 0, 'C');
            PDF::Cell(18, 7, $hitungtugas, 1, 0, 'C');
            PDF::Cell(18, 7, $hitunguts, 1, 0, 'C');
            PDF::Cell(18, 7, $hitunguas, 1, 0, 'C');
            PDF::Cell(10, 7, $hitungnilaiakhir, 1, 0, 'C');
            PDF::Cell(10, 7, mutunilai($hitungnilaiakhir), 1, 0, 'C');
            PDF::Cell(18, 7, kelulusan($hitungnilaiakhir), 1, 0, 'C');
            PDF::Ln();
        }
        $rekap = array_count_values($rekap);
        PDF::Ln(10);
        PDF::SetX(20);
        PDF::SetFont('arialbd', '', 7);
        PDF::Cell(25, 5, 'REKAPITULASI NILAI', 0, 0, 'L');
        PDF::Ln();
        PDF::SetX(10);
        PDF::Cell(15, 5, 'Nilai', 1, 0, 'C');
        PDF::Cell(15, 5, 'Jumlah', 1, 0, 'C');
        PDF::Cell(15, 5, 'Persentase', 1, 0, 'C');
        PDF::SetX(140);
        PDF::Cell(25, 5, 'Palangka Raya, '.Carbon::now()->isoFormat('D MMMM Y'), 0, 0, 'L');
        PDF::Ln();
        PDF::Cell(15, 5, 'A', 1, 0, 'C');
        PDF::Cell(15, 5, jumlahmutu($rekap, 'A'), 1, 0, 'C');
        PDF::Cell(15, 5, ''.persentase($rekap, 'A', $jumlah_mhs).'%', 1, 0, 'C');
        PDF::SetX(140);
        PDF::Cell(25, 5, 'Dosen,', 0, 0, 'L');
        PDF::Ln();
        PDF::Cell(15, 5, 'B+', 1, 0, 'C');
        PDF::Cell(15, 5, jumlahmutu($rekap, 'B+'), 1, 0, 'C');
        PDF::Cell(15, 5, ''.persentase($rekap, 'B+', $jumlah_mhs).'%', 1, 0, 'C');
        PDF::Ln();
        PDF::Cell(15, 5, 'B', 1, 0, 'C');
        PDF::Cell(15, 5, jumlahmutu($rekap, 'B'), 1, 0, 'C');
        PDF::Cell(15, 5, ''.persentase($rekap, 'B', $jumlah_mhs).'%', 1, 0, 'C');
        PDF::Ln();
        PDF::Cell(15, 5, 'C+', 1, 0, 'C');
        PDF::Cell(15, 5, jumlahmutu($rekap, 'C+'), 1, 0, 'C');
        PDF::Cell(15, 5, ''.persentase($rekap, 'C+', $jumlah_mhs).'%', 1, 0, 'C');
        PDF::Ln();
        PDF::Cell(15, 5, 'C', 1, 0, 'C');
        PDF::Cell(15, 5, jumlahmutu($rekap, 'C'), 1, 0, 'C');
        PDF::Cell(15, 5, ''.persentase($rekap, 'C', $jumlah_mhs).'%', 1, 0, 'C');
        PDF::SetX(140);
        PDF::Cell(25, 5, $dosenkoor->dosen_admin->nama, 0, 0, 'L');
        PDF::Ln();
        PDF::Cell(15, 5, 'D', 1, 0, 'C');
        PDF::Cell(15, 5, jumlahmutu($rekap, 'D'), 1, 0, 'C');
        PDF::Cell(15, 5, ''.persentase($rekap, 'D', $jumlah_mhs).'%', 1, 0, 'C');
        PDF::SetX(140);
        PDF::Cell(25, 5, $dosenkoor->dosen_admin->nip, 0, 0, 'L');
        PDF::Ln();
        PDF::Cell(15, 5, 'E', 1, 0, 'C');
        PDF::Cell(15, 5, jumlahmutu($rekap, 'E'), 1, 0, 'C');
        PDF::Cell(15, 5, ''.persentase($rekap, 'E', $jumlah_mhs).'%', 1, 0, 'C');
        PDF::SetTitle('NILAI-'.(strtoupper($mata_kuliah->nama)).'('.(strtoupper($mata_kuliah->kode)).')-KELAS('.($mata_kuliah->kelas).')');
        $nama_file = 'NILAI-'.(strtoupper($mata_kuliah->nama)).'('.(strtoupper($mata_kuliah->kode)).')-KELAS('.($mata_kuliah->kelas).').pdf';
        PDF::Output(storage_path('app').'/public/'.$nama_file, 'F');

        return response()->file(storage_path('app').'/public/'.$nama_file);
    }
}
