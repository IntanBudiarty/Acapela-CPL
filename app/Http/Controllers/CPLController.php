<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\Bobotcpl;
use App\Models\CPLDetail;
use App\Models\MataKuliah;
use App\Imports\CplImport;
use Maatwebsite\Excel\Facades\Excel;    
use Illuminate\Http\Request;
use Validator;

class CPLController extends Controller
{
    public function index()
    {
        // Nilai tetap
        $judul = 'Kelola CPL';
        $parent = 'CPL';

        $tampil = Cpl::all();
        $details = CPLDetail::all(); // Ambil data CPLDetail // Menampilkan hasil per halaman (10 data per halaman)

        return view('cpl.index', [
            'cpl' => $tampil,
            'judul' => $judul,
            'parent' => $parent,
        ]);
    }

    public function tambahindex()
    {
        // Nilai tetap
        $judul = 'Tambah CPL';
        $judulform = 'Form Tambah CPL';
        $parent = 'CPL';
        $subparent = 'Tambah';

        return view('cpl.tambah', [
            'judul' => $judul,
            'judulform' => $judulform,
            'parent' => $parent,
            'subparent' => $subparent,
        ]);
    }

    public function editindex(int $id)
    {
        // Nilai tetap
        $judul = 'Edit CPL';
        $parent = 'CPL';
        $subparent = 'Edit';

        $tampil = Cpl::find($id);
        if (!$tampil) {
            return redirect()->route('cpl.index')->with('error', 'CPL tidak ditemukan');
        }

        return view('cpl.edit', [
            'judul' => $judul,
            'parent' => $parent,
            'subparent' => $subparent,
            'cpl' => $tampil,
        ]);
    }

    public function tambah(Request $request)
    {
        $rules = [
            'kode_cpl' => 'required|string|unique:cpls',
            'nama_cpl' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $cpl = new Cpl();
        $cpl->kode_cpl = $request->input('kode_cpl');
        $cpl->nama_cpl = $request->input('nama_cpl');
        $cpl->save();

        return back()->with('success', 'Data Berhasil Ditambahkan!.');
    }

    public function edit(Request $request, int $id)
    {
        $cpl = Cpl::firstWhere('id', $id);

        $rules = [
            'kode_cpl' => 'required|string|unique:cpls,kode_cpl,'.$cpl->id,
            'nama_cpl' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $cpl->kode_cpl = $request->input('kode_cpl');
        $cpl->nama_cpl = $request->input('nama_cpl');
        $cpl->save();

        return back()->with('success', 'Data Berhasil Diubah!.');
    }

    public function hapus(int $id)
    {
        Cpl::find($id)->delete();

        return redirect()->route('cpl');
    }
    public function detail(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            // Validasi input yang diterima
            $validated = $request->validate([
                'cpmk_id' => 'required|exists:cpmks,id',
                'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            ]);

            // Menyimpan data ke tabel CPLDetail
            CPLDetail::create([
                'cpl_id' => $id,
                'cpmk_id' => $request->input('cpmk_id'),
                'mata_kuliah_id' => $request->input('mata_kuliah_id'),
            ]);

            // Redirect ke halaman yang sama dengan parameter id dan menampilkan pesan sukses
            return redirect()->route('cpl.detail', ['id' => $id])->with('success', 'Data berhasil ditambahkan');
        }

        // Ambil data CPLDetail, CPMK, dan Mata Kuliah untuk tampilan
        $details = CPLDetail::where('cpl_id', $id)->get();
        $cpmks = Cpmk::all();
        $mata_kuliah = MataKuliah::all();

        return view('cpl.detail', compact('details', 'cpmks', 'mata_kuliah', 'id'));
    }

    public function storeDetail(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'cpl_id' => 'required|exists:cpls,id', // Validasi cpl_id harus ada dalam tabel cpls
                'kode_cpmk' => 'required|exists:cpmks,kode_cpmk', // Validasi kode_cpmk harus ada dalam tabel cpmks
                'nama_cpmk' => 'required|exists:cpmks,nama_cpmk', // Validasi nama_cpmk harus ada dalam tabel cpmks
                'kode_mk' => 'required|exists:mata_kuliahs,kode', // Validasi kode_mk harus ada dalam tabel mata_kuliahs
            ]);

            // Menyimpan data CPLDetail
            CPLDetail::create([
                'cpl_id' => $request->cpl_id, // Pastikan cpl_id dikirim dalam request
                'kode_cpmk' => $request->kode_cpmk,
                'nama_cpmk' => $request->nama_cpmk,
                'kode_mk' => $request->kode_mk,
            ]);
            $details = CPLDetail::all();

            // Redirect ke halaman cpl dengan pesan sukses
            return redirect()->route('cpl')->with('success', 'Data berhasil ditambahkan.');

        } catch (\Exception $e) {
            // Jika terjadi error, kembali ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // public function deleteDetail($id)
    // {
    //     $detail = CPLDetail::find($id);
    //     if ($detail) {
    //         $detail->delete();
    //         return redirect()->back()->with('success', 'Data berhasil dihapus!');
    //     }
    //     return redirect()->back()->with('error', 'Data tidak ditemukan!');
    // }

    // In your CPLController


    

    public function showForm()
    {
        return view('cpl.index'); // Mengembalikan form pengelolaan mahasiswa
    }
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
            Excel::import(new CplImport, $file->getRealPath());
            return redirect()->route('cpl')->with('success', 'Data berhasil diimport.');
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
