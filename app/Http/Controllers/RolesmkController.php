<?php

namespace App\Http\Controllers;

use App\Models\DosenAdmin;
use App\Models\MataKuliah;
use App\Models\Rolesmk;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Crypt;
use Illuminate\Http\Request;

class RolesmkController extends Controller
{
    public function index()
    {
        $judul = 'Kelola Koordinator MK';
        $parent = 'Koordinator MK';
        $judulform = 'Cari Data Koordinator MK';

        $ta = TahunAjaran::orderBy('tahun')->get();
        $mk = MataKuliah::orderBy('nama')->get();

        return view('rolesmk.index', [
            'ta' => $ta,
            'mk' => $mk,
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
        $judul = 'Kelola Koordinator MK';
        $parent = 'Koordinator MK';
        $subparent = 'Cari';
        $judulform = 'Cari Data Koordinator MK';
    
        $ta = TahunAjaran::orderBy('tahun')->get();
        $mk = MataKuliah::orderBy('nama')->get();
    
        $id_ta = Crypt::decrypt($request->tahunajaran);
        $id_sem = Crypt::decrypt($request->semester);
        $id_mk = Crypt::decrypt($request->mk);
    
        // Ambil daftar dosen yang sudah menjadi koordinator
        $getDosen = Rolesmk::with('dosen_admin')
            ->where([
                ['tahun_ajaran_id', '=', $id_ta],
                ['mata_kuliah_id', '=', $id_mk],
                ['semester', '=', $id_sem],
            ])
            ->get();
    
        $arraydosen = $getDosen->pluck('dosen_admin_id')->toArray();
    
        // Ambil daftar dosen lain (yang belum menjadi koordinator untuk MK tersebut)
        $getDosenselain = DosenAdmin::whereHas('user', function ($query) {
            $query->whereRaw("status = 'Dosen'");
        })->whereNotIn('id', $arraydosen)->get();
    
        return view('rolesmk.cari', [
            'getDosenselain' => $getDosenselain,
            'getDosen' => $getDosen,
            'ta' => $ta,
            'mk' => $mk,
            'judul' => $judul,
            'judulform' => $judulform,
            'parent' => $parent,
            'subparent' => $subparent,
        ]);
    }
    
    public function store(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'id_ta' => 'required|exists:tahun_ajaran,id',
            'id_mk' => 'required|exists:mata_kuliah,id',
            'id_dosen' => 'required|exists:dosen_admin,id',
            'status' => 'required',
        ]);
    
        // Memeriksa jika status adalah 'koordinator'
        if ($validatedData['status'] == 'koordinator') {
            // Mengizinkan lebih dari satu koordinator, menghapus pengecekan sebelumnya
            $existingKoordinator = Rolesmk::where('id_mk', $validatedData['id_mk'])
                                          ->where('id_ta', $validatedData['id_ta'])
                                          ->where('status', 'koordinator')
                                          ->count();
            // Jika lebih dari satu koordinator, batalkan simpanan data
            if ($existingKoordinator >= 1) {
                return response()->json(['success' => false, 'message' => 'Sudah ada dosen koordinator!']);
            }
        }
    
        // Menyimpan data ke tabel rolesmk
        $role = new Rolesmk();
        $role->id_ta = $validatedData['id_ta'];
        $role->id_mk = $validatedData['id_mk'];
        $role->id_dosen = $validatedData['id_dosen'];
        $role->status = $validatedData['status'];
        $role->semester = $request->semester;
        $role->save();
    
        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan!']);
    }    
    public function tambahRolesmk(Request $request)
{
    // Validasi data
    $validatedData = $request->validate([
        'id_ta' => 'required|exists:tahun_ajaran,id',
        'id_mk' => 'required|exists:mata_kuliah,id',
        'id_dosen' => 'required|exists:dosen_admin,id',
        'status' => 'required',
    ]);

    // Menyimpan data ke database
    try {
        $role = new Rolesmk();
        $role->id_ta = $validatedData['id_ta'];
        $role->id_mk = $validatedData['id_mk'];
        $role->id_dosen = $validatedData['id_dosen'];
        $role->status = $validatedData['status'];
        $role->semester = $request->semester;
        $role->save();

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan!']);
    }
}


    public function hapus(Request $request)
    {
        $id = $request->id;
        $hapus = Rolesmk::find($id)->delete();

        return Response()->json($hapus);
    }
}
