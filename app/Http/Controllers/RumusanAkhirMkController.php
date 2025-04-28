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
    public function index()
    {
        $rumusanAkhirMk = RumusanAkhirMk::with(['mataKuliah', 'cpl', 'cpmk'])->get();

        // Kelompokkan berdasarkan Mata Kuliah, lalu CPL
        $grouped = $rumusanAkhirMk->groupBy('mataKuliah.nama')->map(function ($group) {
            return $group->groupBy('kd_cpl');
        });

        return view('rumusanAkhirMk.index', compact('grouped'));
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
        // Validasi inputan form
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'cpl' => 'required|array',
            'cpl.*.id' => 'required|exists:cpls,id',
            'cpl.*.cpmk' => 'required|array',
            'cpl.*.cpmk.*.id' => 'required|exists:cpmks,id',
            'cpl.*.cpmk.*.skor' => 'required|numeric|min:0',
        ]);

        // Proses penyimpanan data
        $mataKuliah = MataKuliah::find($request->mata_kuliah_id);

        if (!$mataKuliah) {
            return back()->with('error', 'Mata Kuliah tidak ditemukan!');
        }

        try {
            foreach ($request->cpl as $cplItem) {
                $cplId = $cplItem['id'];

                // Ambil nama CPL untuk error message yang lebih informatif
                $cpl = Cpl::find($cplId);
                $namaCpl = $cpl ? $cpl->nama : 'CPL';

                $totalSkorCpl = 0;
                foreach ($cplItem['cpmk'] as $cpmkItem) {
                    $totalSkorCpl += $cpmkItem['skor'];
                }

                // Validasi total skor CPL
                if ($totalSkorCpl > 100) {
                    return back()->with('error', "Total skor untuk CPL '{$namaCpl}' melebihi 100. Total saat ini: {$totalSkorCpl}.");
                }

                // Simpan data Rumusan Akhir MK
                foreach ($cplItem['cpmk'] as $cpmkItem) {
                    $cpmkId = $cpmkItem['id'];
                    $skor = $cpmkItem['skor'];

                    $rumusanAkhirMk = RumusanAkhirMk::create([
                        'mata_kuliah_id' => $request->mata_kuliah_id,
                        'nama_mk' => $mataKuliah->nama,
                        'kd_cpl' => $cplId,
                        'kd_cpmk' => $cpmkId,
                        'skor_maksimal' => $skor,
                        'total_skor' => $skor,
                    ]);

                    RumusanAkhirCpl::create([
                        'rumusan_akhir_mk_id' => $rumusanAkhirMk->id,
                        'kd_cpl' => $cplId,
                        'mata_kuliah_id' => $request->mata_kuliah_id,
                        'nama_mk' => $mataKuliah->nama,
                        'cpmk' => $cpmkId,
                        'skor_maksimal' => $skor,
                        'total_skor' => $skor,
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
            $rumusanAkhirMk->delete();

            return redirect()->route('rumusanAkhirMk.index')->with('success', 'Data berhasil dihapus.');
        } catch (QueryException $e) {
            if ($e->getCode() === "23000") {
                return redirect()->route('rumusanAkhirMk.index')->with('error', 'Data tidak dapat dihapus karena masih memiliki relasi dengan data lain!');
            }

            return redirect()->route('rumusanAkhirMk.index')->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
