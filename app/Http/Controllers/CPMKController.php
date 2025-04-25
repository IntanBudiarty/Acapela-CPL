<?php

namespace App\Http\Controllers;

use App\Models\Bobotcpl;
use App\Models\Btp;
use App\Models\Cpmk;
use App\Imports\CpmkImport;
use App\Models\Cpl;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Validator;

class CPMKController extends Controller
{
    public function import(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');

        try {
            // Proses impor file
            // Excel::import(new ImportMahasiswa, $request->file('file'));
            Excel::import(new CpmkImport, $file->getRealPath());
            return redirect()->route('cpmk')->with('success', 'Data berhasil diimport.');
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function index()
    {
        // Nilai tetap
        $judul = 'Pemetaan CPL-CPMK-MK';
        $parent = 'CPMK';

        // Ambil data CPMK dengan relasi CPL dan Mata Kuliah
        $tampil = Cpmk::with(['mataKuliah', 'cpl'])->get();

        // Ambil data CPL dengan relasi CPMK dan Mata Kuliah
        $cpls = Cpl::with(['cpmks.mataKuliah'])->get();

        return view('cpmk.index', [
            'cpmk' => $tampil,
            'judul' => $judul,
            'parent' => $parent,
            'cpls' => $cpls,
        ]);
    }


    public function tambahindex()
    {
        // Nilai tetap
        $judul = 'Tambah CPMK';
        $judulform = 'Form Tambah CPMK';
        $parent = 'CPMK';
        $subparent = 'Tambah';

        $mk = MataKuliah::orderBy('kode')->get();
        $cpls = Cpl::all();

        return view('cpmk.tambah', [
            'cpl'=>$cpls,
            'mk' => $mk,
            'judul' => $judul,
            'judulform' => $judulform,
            'parent' => $parent,
            'subparent' => $subparent,
        ]);
    }

    public function editindex(int $id)
    {
        // Nilai tetap
        $judul = 'Edit CPMK';
        $parent = 'CPMK';
        $subparent = 'Edit';

        $mk = MataKuliah::all();
        $cpmk = Cpmk::with('mataKuliah')->findOrFail($id);
        $cpl = Cpl::all();

        return view('cpmk.edit', [
            'judul' => $judul,
            'parent' => $parent,
            'subparent' => $subparent,
            'cpmk' => $cpmk,
            'mk' => $mk,
            'cpls' => $cpl
        ]);
    }
    public function showCplData()
    {
        // Mengambil data CPL beserta CPMK dan Matakuliah yang terkait
        $cpls = Cpl::with('cpmks')->get();

        return view('cpl.index', compact('cpls'));
    }

    public function tambah(Request $request)
    {
        $id_mk = $request->input('mata_kuliah');
        $kode_cpmk = $request->input('kode_cpmk');
        $kode_cpl = $request->input('kode_cpl');  // Ambil kode CPL dari form
        if ($kode_cpl) {
            $cpl = Cpl::where('kode_cpl', $kode_cpl)->first();

        }
        $cpl_id = $request->input('cpl_id');  // pakai langsung cpl_id

        // Cek jika mata kuliah dengan kode CPMK sudah ada
        $cek_ada = Cpmk::whereHas('mataKuliah', function ($query) use ($id_mk) {
            $query->where('mata_kuliah_id', $id_mk);
        })->where('kode_cpmk', $kode_cpmk)->exists();

        if ($cek_ada) {
            return back()->with('error', 'Mata Kuliah Dengan Kode CPMK Yang Dimasukkan Sudah Ada!');
        }

        $rules = [
            'kode_cpl' => 'required|string',  // Validasi untuk kode cpl
            'kode_cpmk' => 'required|string',
            'nama_cpmk' => 'required|string',
            'mata_kuliah' => 'required|array|min:1'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cpmk = new Cpmk();
        $cpmk->kode_cpmk = $kode_cpmk;
        $cpmk->nama_cpmk = $request->input('nama_cpmk');
        $cpmk->save();

        $mataKuliahIds = $request->input('mata_kuliah');
        $cpmk->mataKuliah()->sync($mataKuliahIds); // Menyimpan hubungan many-to-many
    
        // Mengaitkan CPL ke CPMK
        if ($kode_cpl) {
            $cpl = Cpl::where('kode_cpl', $kode_cpl)->first();
            if ($cpl) {
                // Menyimpan cpl_id ke dalam tabel cpmk
                $cpmk->cpl_id = $cpl->id;
                $cpmk->save();
            }
        }

        return redirect()->route('cpmk.index')->with('success', 'Data CPMK berhasil ditambahkan!');
    }
   

    public function edit(Request $request, int $id)
    {
        $id_mk = $request->input('mata_kuliah');
        $kode_cpmk = $request->input('kode_cpmk');

        // Cek jika mata kuliah dengan kode CPMK sudah ada
        $cek_ada = Cpmk::whereHas('mataKuliah', function ($query) use ($id_mk) {
            $query->where('mata_kuliah_id', $id_mk);
        })->where('kode_cpmk', $kode_cpmk)->exists();

        if ($cek_ada) {
            return back()->with('error', 'Mata Kuliah Dengan Kode CPMK Yang Dimasukkan Sudah Ada!');
        }

        $rules = [
            'Kode_cpl' => 'required|string',
            'kode_cpmk' => 'required|string',
            'nama_cpmk' => 'required|string',
            'mata_kuliah' => 'required|array|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cpmk = Cpmk::findOrFail($id);
        $cpmk->code_cpl = $request->input('kode_cpl');
        $cpmk->kode_cpmk = $request->input('kode_cpmk');
        $cpmk->nama_cpmk = $request->input('nama_cpmk');
        $cpmk->save();

        // Menyimpan relasi many-to-many
        $mataKuliahIds = $request->input('mata_kuliah'); // Array of mata kuliah IDs
        $cpmk->mataKuliah()->sync($mataKuliahIds); // Sync relasi many-to-many
        $cpmk->cpl_id = $request->input('cpl_id');

        return back()->with('success', 'Data Berhasil Diubah!');
    }
    public function hapus($id)
    {
        try {
            $cpmk = Cpmk::findOrFail($id);
            $cpmk->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
