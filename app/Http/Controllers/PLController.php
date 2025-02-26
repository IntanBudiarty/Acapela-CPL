<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PL; 
use Validator; 
class PLController extends Controller
{
    public function index()
    {
        // Nilai tetap
        $judul = 'Profil  lulusan';
        $parent = 'PL';

        $tampil = pl::paginate(10);  // Menampilkan hasil per halaman (10 data per halaman)
    
        return view('pl.index', [
            'pl' => $tampil,
            'judul' => $judul,
            'parent' => $parent,
        ]);
    }

    public function tambahindex()
    {
        // Nilai tetap
        $judul = 'Tambah PL';
        $judulform = 'Form Tambah PL';
        $parent = 'PL';
        $subparent = 'Tambah';

        return view('pl.tambah', [
            'judul' => $judul,
            'judulform' => $judulform,
            'parent' => $parent,
            'subparent' => $subparent,
        ]);
    }

    public function editindex(int $id)
    {
        // Nilai tetap
        $judul = 'Edit PL';
        $parent = 'PL';
        $subparent = 'Edit';

        $tampil = pl::find($id);
        if (!$tampil) {
            return redirect()->route('pl.index')->with('error', 'PL tidak ditemukan');
        }

        return view('pl.edit', [
            'judul' => $judul,
            'parent' => $parent,
            'subparent' => $subparent,
            'pl' => $tampil,
        ]);
    }
    public function tambah(Request $request)
    {
        $rules = [
            'kode_pl' => 'required|string|unique:pl',
            'deskripsi' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $pl = new pl();
        $pl->kode_pl = $request->input('kode_pl');
        $pl->deskripsi = $request->input('deskripsi');
        $pl->save();

        return back()->with('success', 'Data Berhasil Ditambahkan!.');
    }

    public function edit(Request $request, int $id)
    {
        $request->validate([
            'kode_pl' => 'required', 
            'deskripsi' => 'required',
        ]);
        $pl = pl::firstWhere('id', $id);

        $rules = [
            'kode_pl' => 'required|string|unique:pl,kode_pl,'.$pl->id,
            'deskripsi' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $pl->kode_pl = $request->input('kode_pl');
        $pl->deskripsi = $request->input('deskripsi');
        $pl->save();

        return back()->with('success', 'Data Berhasil Diubah!.');
    }

    public function hapus(int $id)
    {
        pl::find($id)->delete();

        return redirect()->route('pl');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_pl' => 'required',
            'deskripsi' => 'required',
        ]);

        // Menyimpan data PL ke database
        PL::create([
            'kode_pl' => $request->kode_pl,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('pl.index')->with('success', 'PL berhasil ditambahkan!');
    }
}


