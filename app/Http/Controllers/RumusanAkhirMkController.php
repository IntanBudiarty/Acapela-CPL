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
        $rumusanAkhirMk = RumusanAkhirMk::findOrFail($id);
        $cpls = Cpl::all();
        $cpmks = Cpmk::all();
        $mataKuliahs = MataKuliah::all();

        // Persiapkan array repeater
        $repeater = [];
        $cplCodes = is_array($rumusanAkhirMk->kd_cpl) ? $rumusanAkhirMk->kd_cpl : explode(',', $rumusanAkhirMk->kd_cpl);
        $cpmkCodes = is_array($rumusanAkhirMk->kd_cpmk) ? $rumusanAkhirMk->kd_cpmk : explode(',', $rumusanAkhirMk->kd_cpmk);
        $skorMaksimal = $rumusanAkhirMk->skor_maksimal ? json_decode($rumusanAkhirMk->skor_maksimal, true) : [];

        foreach ($cplCodes as $cpl) {
            $repeater[] = [
                'cpl' => $cpl,
                'cpmks' => []
            ];
        }

        // Logic untuk menyesuaikan CPMK ke CPL
        $cplCount = count($repeater);
        $cpmkPerCpl = ceil(count($cpmkCodes) / $cplCount);

        $index = 0;
        foreach ($repeater as &$r) {
            for ($i = 0; $i < $cpmkPerCpl && $index < count($cpmkCodes); $i++, $index++) {
                $r['cpmks'][] = [
                    'cpmk' => $cpmkCodes[$index],
                    'skor' => $skorMaksimal[$cpmkCodes[$index]] ?? null
                ];
            }
        }

        return view('rumusanAkhirMk.edit', compact('rumusanAkhirMk', 'mataKuliahs', 'cpls', 'cpmks', 'repeater'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'cpl_id' => 'required|exists:cpls,id',
            'cpmk_id' => 'required|exists:cpmks,id',
            'skor_maksimal' => 'required|numeric',
        ]);

        $rumusanAkhirMk = RumusanAkhirMk::findOrFail($id);

        $rumusanAkhirMk->mata_kuliah_id = $validatedData['mata_kuliah_id'];
        $rumusanAkhirMk->kd_cpl = $validatedData['cpl_id'];
        $rumusanAkhirMk->kd_cpmk = $validatedData['cpmk_id'];
        $rumusanAkhirMk->skor_maksimal = $validatedData['skor_maksimal'];
        $rumusanAkhirMk->total_skor = $validatedData['skor_maksimal'];

        $rumusanAkhirMk->save();

        return redirect()->route('rumusanAkhirMk.index')->with('success', 'Data berhasil diperbarui.');
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
