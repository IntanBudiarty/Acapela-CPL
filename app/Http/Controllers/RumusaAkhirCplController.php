<?php

namespace App\Http\Controllers;

use App\Models\RumusanAkhirCpl;
use App\Models\Cpl;
use App\Models\RumusanAkhirMk;
use App\Models\MataKuliah;
use App\Models\Cpmk;
use Illuminate\Http\Request;

class RumusaAkhirCplController extends Controller
{
    public function index()
    {
        // Ambil data beserta relasi (rumusanAkhirMk -> mataKuliah)
        $rumusanAkhirCpl = RumusanAkhirCpl::with(['rumusanAkhirMk.mataKuliah'])->get();

        // Kelompokkan data berdasarkan kd_cpl dan hitung total skor untuk tiap grup
        $groupedData = $rumusanAkhirCpl->groupBy('kd_cpl')->map(function ($group) {
            return [
                'total_skor' => $group->sum('skor_maksimal'),
                'records'    => $group, // koleksi data tiap baris (per cpmk)
            ];
        });

        return view('rumusanAkhirCpl.index', compact('groupedData'));
    }




    public function importDataFromRumusanAkhirMk()
    {
        try {
            // Ambil semua data dari rumusan_akhir_mk
            $rumusanAkhirMkData = RumusanAkhirMk::all();

            if ($rumusanAkhirMkData->isEmpty()) {
                return back()->with('error', 'Data rumusan_akhir_mk kosong!');
            }

            $dataToInsert = []; // Array untuk batch insert

            foreach ($rumusanAkhirMkData as $data) {
                // Pastikan data valid sebelum dipindahkan
                if (!isset($data->kd_cpl) || !isset($data->kd_cpmk)) {
                    continue; // Lewati data yang tidak valid
                }

                // Pecah data kd_cpl yang dipisahkan koma
                $kdCplArray = explode(',', $data->kd_cpl);

                // Proses setiap kode CPL
                foreach ($kdCplArray as $kdCpl) {
                    // Trim spasi agar bersih
                    $kdCpl = trim($kdCpl);

                    if (!empty($kdCpl)) {
                        // Gunakan kdCpl apa adanya, jangan digenerate ulang
                        $dataToInsert[] = [
                            'kd_cpl'            => $kdCpl,
                            'mata_kuliah_id'    => $data->mata_kuliah_id,
                            'nama_mk'           => $data->nama_mk,
                            'cpmk'              => $data->kd_cpmk,
                            'skor_maksimal'     => $data->skor_maksimal,
                            'total_skor'        => $data->total_skor,
                            'created_at'        => now(),
                            'updated_at'        => now(),
                            'rumusan_akhir_mk_id' => $data->id,
                        ];
                    }
                }
            }

            // Jika ada data yang valid, insert ke rumusan_akhir_cpl
            if (!empty($dataToInsert)) {
                RumusanAkhirCpl::insert($dataToInsert);
            }

            return redirect()->route('rumusanAkhirCpl.index')->with('success', 'Data berhasil dipindahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
            return back()->with('error', 'Mata Kuliah tidak dapat ditemukan!');
        }

        try {
            // Menyimpan data Rumusan Akhir MK
            $rumusanAkhirMk = RumusanAkhirMk::create($request->all());


            // Jika Rumusan Akhir MK berhasil disimpan, lanjutkan ke rumusan_akhir_cpl
            $dataToInsert = [];

            // Loop untuk menyimpan data berdasarkan CPMK yang dipilih
            foreach ($request->kd_cpmk as $cpmkKode) {
                // Ambil array kode cpl yang dipilih
                $kdCplArray = $request->kd_cpl;

                // Temukan data CPMK berdasarkan kode
                $cpmk = Cpmk::where('kode_cpmk', $cpmkKode)->first();

                if ($cpmk) {
                    // Loop untuk setiap kode cpl yang dipilih dan simpan ke rumusan_akhir_cpl
                    foreach ($kdCplArray as $index => $kdCpl) {
                        // Tentukan kode CPL baru (CPL01, CPL02, dst.)
                        $nextCplCode = 'CPL' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

                        $dataToInsert[] = [
                            'rumusan_akhir_mk_id' => $rumusanAkhirMk->id,
                            'kd_cpl' => $nextCplCode, // Kode CPL
                            'mata_kuliah_id' => $rumusanAkhirMk->mata_kuliah_id,
                            'nama_mk' => $rumusanAkhirMk->nama_mk,
                            'cpmk' => $cpmk->kode_cpmk,
                            'skor_maksimal' => $rumusanAkhirMk->skor_maksimal,
                            'total_skor' => $rumusanAkhirMk->total_skor,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            // Masukkan data ke rumusan_akhir_cpl
            if (!empty($dataToInsert)) {
                RumusanAkhirCpl::insert($dataToInsert);
            }

            // Redirect ke halaman index dengan pesan sukses
            return redirect()->route('rumusanAkhirMk.index')->with('success', 'Data Rumusan Akhir MK dan CPL berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
