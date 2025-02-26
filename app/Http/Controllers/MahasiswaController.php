<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\Mahasiswa;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use App\Imports\MahasiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    public function index()
    {
        // Nilai tetap
        $judul = 'Kelola Mahasiswa';
        $parent = 'Mahasiswa';

        $tampil = Mahasiswa::get();

        return view('mahasiswa.index', [
            'mahasiswa' => $tampil,
            'judul' => $judul,
            'parent' => $parent,
        ]);
        $mahasiswas = Mahasiswa::all(); // Mengambil semua data mahasiswa
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function tambahindex()
    {
        // Nilai tetap
        $judul = 'Tambah Mahasiswa';
        $judulform = 'Form Tambah Mahasiswa';
        $parent = 'Mahasiswa';
        $subparent = 'Tambah';

        return view('mahasiswa.tambah', [
            'judul' => $judul,
            'judulform' => $judulform,
            'parent' => $parent,
            'subparent' => $subparent,
        ]);
    }

    public function editindex(int $id)
    {
        // Nilai tetap
        $judul = 'Edit Mahasiswa';
        $parent = 'Mahasiswa';
        $subparent = 'Edit';

        $tampil = Mahasiswa::find($id);

        return view('mahasiswa.edit', [
            'judul' => $judul,
            'parent' => $parent,
            'subparent' => $subparent,
            'mahasiswa' => $tampil,
        ]);
    }

    public function tambah(Request $request)
    {
        $rules = [
            'nim' => 'required|string|unique:mahasiswas',
            'nama' => 'required|string',
            'angkatan' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $mhs = new Mahasiswa();
        $mhs->nim = $request->input('nim');
        $mhs->nama = $request->input('nama');
        $mhs->angkatan = $request->input('angkatan');
        $mhs->save();

        return back()->with('success', 'Data Berhasil Ditambahkan!.');
    }

    public function edit(Request $request, int $id)
    {
        $mhs = Mahasiswa::firstWhere('id', $id);

        $rules = [
            'nim' => 'required|string|unique:mahasiswas,nim,'.$mhs->id,
            'nama' => 'required|string',
            'angkatan' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $mhs->nim = $request->input('nim');
        $mhs->nama = $request->input('nama');
        $mhs->angkatan = $request->input('angkatan');
        $mhs->save();

        return back()->with('success', 'Data Berhasil Diubah!');
    }

    public function hapus(int $id)
    {
        Mahasiswa::find($id)->delete();

        return redirect()->route('mhs');
    }


    public function addMataKuliah(Request $request, $mahasiswaId)
    {
        $mahasiswa = Mahasiswa::with('mataKuliahs')->find($mahasiswaId);

        // Hitung total SKS yang sudah diambil mahasiswa
        $totalSKS = $mahasiswa->mataKuliahs->sum('sks');

        // Cek jika penambahan SKS membuat totalnya lebih dari 24
        $mataKuliah = MataKuliah::find($request->mata_kuliah_id);
        if ($totalSKS + $mataKuliah->sks > 24) {
            return redirect()->back()->with('error', 'Total SKS tidak boleh lebih dari 24.');
        }

        // Jika validasi lolos, tambahkan mata kuliah
        $mahasiswa->mataKuliahs()->attach($mataKuliah->id);

        return redirect()->route('mahasiswa.detail', $mahasiswaId)->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function removeMataKuliah($mahasiswaId, $mataKuliahId)
    {
        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
        $mataKuliah = MataKuliah::findOrFail($mataKuliahId);

        // Menghapus mata kuliah dari KRS mahasiswa
        $mahasiswa->mataKuliahs()->detach($mataKuliah->id);

        return redirect()->route('mahasiswa.detail', $mahasiswa->id)
                        ->with('success', 'Mata Kuliah berhasil dihapus!');
    }

    public function showDetail($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mataKuliah = $mahasiswa->mataKuliah; // Misalkan ada relasi 'mataKuliah' pada model Mahasiswa
        return view('mahasiswa.detail', compact('mahasiswa', 'mataKuliah'));
    }
    // Di Controller Mahasiswa
    public function detail($id)
    {
        // Ambil data mahasiswa berdasarkan ID
        $mahasiswa = Mahasiswa::with('mataKuliahs')->find($id);

        // Pastikan mahasiswa ditemukan
        if (!$mahasiswa) {
            return redirect()->route('mhs')->with('error', 'Mahasiswa tidak ditemukan');
        }

        // Ambil semua mata kuliah untuk ditampilkan di form
        $mataKuliahs = MataKuliah::all();
        $totalSKS = $mahasiswa->mataKuliahs->sum('sks');

        return view('mahasiswa.detail', compact('mahasiswa', 'mataKuliahs', 'totalSKS'));
    }
    public function mataKuliah($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mataKuliah = $mahasiswa->mataKuliah; // Pastikan relasi sudah didefinisikan di model Mahasiswa
        return view('mahasiswa.mata_kuliah', compact('mahasiswa', 'mataKuliah'));
    }
    public function showMataKuliah($id)
    {
        // Ambil data mahasiswa beserta mata kuliah yang diambilnya
        $mahasiswa = Mahasiswa::with('mataKuliahs')->findOrFail($id);

        // Hitung total SKS yang diambil mahasiswa
        $sum_sks = $mahasiswa->mataKuliahs->sum('sks'); // Menggunakan relasi 'mataKuliahs'

        // Ambil semua mata kuliah untuk dropdown (jika dibutuhkan)
        $mataKuliahs = MataKuliah::all();

        // Kembalikan tampilan dengan data yang diperlukan
        return view('mahasiswa.detail', compact('mahasiswa', 'mataKuliahs', 'sum_sks'));
    }

    public function showForm()
    {
        return view('mahasiswa.index'); // Mengembalikan form pengelolaan mahasiswa
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
            Excel::import(new MahasiswaImport, $file->getRealPath());
            return redirect()->route('mhs')->with('success', 'Data berhasil diimport.');
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

}

