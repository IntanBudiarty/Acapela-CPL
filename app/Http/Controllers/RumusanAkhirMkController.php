<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\RumusanAkhirCpl;
use App\Models\RumusanAkhirMk;
use App\Models\Cpl;
use App\Models\Cpmk;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;

class RumusanAkhirMkController extends Controller
{
    // Controller
    // public function index()
    // {
    //     $rumusanAkhirMkGrouped = RumusanAkhirMk::with(['mataKuliah.cpls', 'mataKuliah.cpmks'])
    //         ->get()
    //         ->groupBy('mata_kuliah_id');  // Mengelompokkan berdasarkan mata_kuliah_id

    //     return view('rumusanAkhirMk.index', compact('rumusanAkhirMkGrouped'));
    // }
    public function index()
    {
        $rumusanAkhirMkGrouped = RumusanAkhirMk::with(['mataKuliah.cpls', 'mataKuliah.cpmks'])
            ->get()
            ->groupBy('mata_kuliah_id');  // Mengelompokkan berdasarkan mata_kuliah_id

        return view('rumusanAkhirMk.index', compact('rumusanAkhirMkGrouped'));
    }

    public function create()
    {
        $mataKuliah = MataKuliah::all();
        $cpls = Cpl::all();
        $cpmks = Cpmk::all();

        return view('rumusanAkhirMk.tambah', compact('mataKuliah', 'cpls', 'cpmks'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'kd_cpl' => 'required|array',
            'kd_cpl.*' => 'exists:cpls,kode_cpl',
            'kd_cpmk' => 'required|array',
            'kd_cpmk.*' => 'exists:cpmks,kode_cpmk',
            'skor_maksimal' => 'required|array',
            'skor_maksimal.*' => 'numeric',
        ]);

        // Temukan data Mata Kuliah berdasarkan ID
        $mataKuliah = MataKuliah::find($request->mata_kuliah_id);

        if (!$mataKuliah) {
            return back()->with('error', 'Mata Kuliah tidak ditemukan!');
        }

        try {
            // Simpan data Rumusan Akhir MK
            $rumusanAkhirMk = RumusanAkhirMk::create([
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'nama_mk' => $mataKuliah->nama,
                'kd_cpl' => implode(',', $request->kd_cpl),
                'kd_cpmk' => implode(',', $request->kd_cpmk),
                'skor_maksimal' => array_sum($request->skor_maksimal),
                'total_skor' => array_sum($request->skor_maksimal),
            ]);

            // Simpan data ke Rumusan Akhir CPL
            foreach ($request->kd_cpl as $cplKode) {
                foreach ($request->kd_cpmk as $cpmkKode) {
                    RumusanAkhirCpl::create([
                        'rumusan_akhir_mk_id' => $rumusanAkhirMk->id,
                        'kd_cpl' => $cplKode,
                        'mata_kuliah_id' => $request->mata_kuliah_id,
                        'nama_mk' => $mataKuliah->nama,
                        'cpmk' => $cpmkKode,
                        'skor_maksimal' => $request->skor_maksimal[$cpmkKode],
                        'total_skor' => array_sum($request->skor_maksimal),
                    ]);
                }
            }

            return redirect()->route('rumusanAkhirMk.index')->with('success', 'Rumusan Akhir MK dan CPL berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Mengambil data RumusanAkhirMk beserta relasi Mata Kuliah, CPL, dan CPMK yang terkait
        $rumusanAkhirMk = RumusanAkhirMk::with(['mataKuliah', 'cpl', 'cpmk'])->findOrFail($id);

        // Mengambil semua Mata Kuliah, CPL, dan CPMK untuk pilihan di form
        $mataKuliahs = MataKuliah::all();
        $cpls = Cpl::all();
        $cpmks = Cpmk::all();

        // Mengirim data ke view untuk ditampilkan di halaman edit
        return view('rumusanAkhirMk.edit', compact('rumusanAkhirMk', 'mataKuliahs', 'cpls', 'cpmks'));
    }



    public function update(Request $request, $id)
    {
        // Validasi inputan
        $validatedData = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'cpl_id' => 'required|exists:cpls,id',
            'cpmk_id' => 'required|exists:cpmks,id',
            'skor_maksimal' => 'required|numeric',
        ]);

        // Mengambil data RumusanAkhirMk berdasarkan ID
        $rumusanAkhirMk = RumusanAkhirMk::findOrFail($id);

        // Sinkronkan CPL dan CPMK yang dipilih dengan Mata Kuliah
        $rumusanAkhirMk->cpl()->sync($validated['cpl']);
        $rumusanAkhirMk->cpmk()->sync($validated['cpmk']);

        // Hitung total skor maksimal dari CPMK yang dipilih
        $totalSkor = 0;
        foreach ($rumusanAkhirMk->cpl as $cpmk) {
            $totalSkor += $cpmk->skor_maks;
        }

        // Simpan total skor yang dihitung
        $rumusanAkhirMk->total_skor = $totalSkor;
        
        // Update data rumusan akhir MK
        $rumusanAkhirMk->save();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('rumusanAkhirMk.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $rumusanAkhirMk = RumusanAkhirMk::findOrFail($id);

            // Hapus data
            $rumusanAkhirMk->delete();

            // Berikan notifikasi jika berhasil
            return redirect()->route('rumusanAkhirMk.index')->with('success', 'Data berhasil dihapus.');
        } catch (QueryException $e) {
            // Tangkap jika terjadi kesalahan relasi
            if ($e->getCode() === "23000") { // Constraint violation
                return redirect()->route('rumusanAkhirMk.index')->with('error', 'Data tidak dapat dihapus karena masih memiliki relasi dengan data lain!');
            }

            // Tangkap kesalahan umum lainnya
            return redirect()->route('rumusanAkhirMk.index')->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
