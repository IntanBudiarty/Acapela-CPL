<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RumusanAkhirMkImport;
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



    //     public function update(Request $request, $id)
    // {
    //     // Validasi inputan form
    //     $validatedData = $request->validate([
    //         'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
    //         'cpl' => 'required|array',
    //         'cpl.*.id' => 'required|exists:cpls,id',
    //         'cpl.*.cpmk' => 'required|array',
    //         'cpl.*.cpmk.*.id' => 'required|exists:cpmks,id',
    //         'cpl.*.cpmk.*.skor' => 'required|numeric|min:0',
    //     ]);

    //     // Mulai transaksi
    //     \DB::beginTransaction();

    //     try {
    //         // Update data utama RumusanAkhirMk
    //         $rumusanAkhirMk = RumusanAkhirMk::findOrFail($id);
    //         $rumusanAkhirMk->update([
    //             'mata_kuliah_id' => $validatedData['mata_kuliah_id'],
    //             'skor_maksimal' => $validatedData['skor_maksimal'], // pastikan ada input untuk skor_maksimal
    //             'total_skor' => $validatedData['skor_maksimal'],   // atau sesuaikan logika untuk total skor
    //         ]);

    //         // Menghapus data relasi di tabel rumusan_akhir_cpl sebelum diupdate
    //         RumusanAkhirCpl::where('rumusan_akhir_mk_id', $id)->delete();

    //         // Loop untuk menyimpan data CPL dan CPMK terkait
    //         foreach ($validatedData['cpl'] as $cplItem) {
    //             foreach ($cplItem['cpmk'] as $cpmkItem) {
    //                 RumusanAkhirCpl::create([
    //                     'rumusan_akhir_mk_id' => $rumusanAkhirMk->id,
    //                     'kd_cpl' => Cpl::find($cplItem['id'])->kode_cpl,
    //                     'mata_kuliah_id' => $validatedData['mata_kuliah_id'],
    //                     'nama_mk' => MataKuliah::find($validatedData['mata_kuliah_id'])->nama,
    //                     'cpmk' => Cpmk::find($cpmkItem['id'])->kode_cpmk,
    //                     'skor_maksimal' => $cpmkItem['skor'],
    //                     'total_skor' => $cpmkItem['skor'],
    //                 ]);
    //             }
    //         }

    //         // Commit perubahan jika semua lancar
    //         \DB::commit();

    //         // Redirect ke index dengan pesan sukses
    //         return redirect()->route('rumusanAkhirMk.index')->with('success', 'Data berhasil diperbarui!');

    //     } catch (\Exception $e) {
    //         \DB::rollBack(); // Rollback jika ada error
    //         return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }


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
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        Excel::import(new RumusanAkhirMkImport, $request->file('file'));

        return back()->with('success', 'Data berhasil diimpor!');
    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
}
