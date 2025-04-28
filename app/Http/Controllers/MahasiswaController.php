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
        $judul = 'Kelola Mahasiswa';
        $parent = 'Mahasiswa';

        // Mengelompokkan mahasiswa berdasarkan angkatan
        $mahasiswa = Mahasiswa::orderBy('angkatan', 'desc')->get()->groupBy('angkatan');

        return view('mahasiswa.index', compact('mahasiswa', 'judul', 'parent'));
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
            'kelas' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        // Mendapatkan NIM terakhir dari mahasiswa yang ada pada angkatan yang sama
        $angkatan = $request->input('angkatan');
        $lastMahasiswa = Mahasiswa::where('angkatan', $angkatan)->orderBy('nim', 'desc')->first();
        
        // Tentukan NIM baru, jika belum ada mahasiswa dengan angkatan tersebut, mulai dari 1
        $newNim = $lastMahasiswa ? (int)substr($lastMahasiswa->nim, -4) + 1 : 1;
        $newNim = str_pad($newNim, 4, '0', STR_PAD_LEFT); // Pastikan NIM selalu 4 digit, seperti 0001, 0002, dll
        $newNim = $angkatan . $newNim; // Gabungkan angkatan dan NIM

        // Simpan data mahasiswa baru
        $mhs = new Mahasiswa();
        $mhs->nim = $newNim;
        $mhs->nama = $request->input('nama');
        $mhs->angkatan = $angkatan;
        $mhs->kelas = $request->input('kelas');
        $mhs->save();

        return back()->with('success', 'Data Mahasiswa Berhasil Ditambahkan!');
    }


    public function edit(Request $request, int $id)
    {
        $mhs = Mahasiswa::firstWhere('id', $id);

        $rules = [
            'nim' => 'required|string|unique:mahasiswas,nim,' . $mhs->id,
            'nama' => 'required|string',
            'angkatan' => 'required|integer',
            'kelas' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $mhs->nim = $request->input('nim');
        $mhs->nama = $request->input('nama');
        $mhs->angkatan = $request->input('angkatan');
        $mhs->kelas = $request->input('kelas');
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
        $mahasiswa = Mahasiswa::with('mataKuliahs')->findOrFail($mahasiswaId);
        $mataKuliah = MataKuliah::findOrFail($request->mata_kuliah_id);

        // Ambil semua mahasiswa dengan angkatan yang sama
        $mahasiswaLain = Mahasiswa::with('mataKuliahs')
            ->where('angkatan', $mahasiswa->angkatan)
            ->get();

        $terdaftar = 0;
        $tidakTerdaftarKarenaPenuh = 0;
        $sudahAmbil = 0;

        foreach ($mahasiswaLain as $mhs) {
            $totalSKS = $mhs->mataKuliahs->sum('sks');

            // Cek kalau matkul sudah diambil, skip
            if ($mhs->mataKuliahs()->where('mata_kuliah_id', $mataKuliah->id)->exists()) {
                $sudahAmbil++;
                continue;
            }

            // Cek kalau SKS masih bisa ditambah
            if ($totalSKS + $mataKuliah->sks <= 24) {
                $mhs->mataKuliahs()->attach($mataKuliah->id);
                $terdaftar++;
            } else {
                $tidakTerdaftarKarenaPenuh++;
            }
        }

        $pesan = "$terdaftar mahasiswa berhasil ditambahkan mata kuliah $mataKuliah->nama.";
        if ($tidakTerdaftarKarenaPenuh > 0) {
            $pesan .= " $tidakTerdaftarKarenaPenuh mahasiswa tidak ditambahkan karena melebihi batas SKS.";
        }
        if ($sudahAmbil > 0) {
            $pesan .= " $sudahAmbil mahasiswa sudah pernah mengambil mata kuliah ini.";
        }

        return redirect()->route('mahasiswa.detail', $mahasiswaId)->with('success', $pesan);
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
        $mataKuliah = $mahasiswa->mataKuliah; 
        return view('mahasiswa.detail', compact('mahasiswa', 'mataKuliah'));
    }
  
    public function detail($id)
    {
       
        $mahasiswa = Mahasiswa::with('mataKuliahs')->find($id);

        
        if (!$mahasiswa) {
            return redirect()->route('mhs')->with('error', 'Mahasiswa tidak ditemukan');
        }

       
        $mataKuliahs = MataKuliah::all();
        $totalSKS = $mahasiswa->mataKuliahs->sum('sks');

        return view('mahasiswa.detail', compact('mahasiswa', 'mataKuliahs', 'totalSKS'));
    }
    public function mataKuliah($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mataKuliah = $mahasiswa->mataKuliah; 
        return view('mahasiswa.mata_kuliah', compact('mahasiswa', 'mataKuliah'));
    }
    public function showMataKuliah($id)
    {
        
        $mahasiswa = Mahasiswa::with('mataKuliahs')->findOrFail($id);

        
        $sum_sks = $mahasiswa->mataKuliahs->sum('sks'); 

       
        $mataKuliahs = MataKuliah::all();

        
        return view('mahasiswa.detail', compact('mahasiswa', 'mataKuliahs', 'sum_sks'));
    }

    public function showForm()
    {
        return view('mahasiswa.index'); // Mengembalikan form pengelolaan mahasiswa
    }

    public function import(Request $request)
    {
        // Validasi input
        $request->validate([
            'angkatan' => 'required|integer',
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);
    
        $file = $request->file('file');
        $angkatan = $request->angkatan; // Ambil angkatan dari form
    
        try {
            // Proses impor file dengan menyertakan angkatan sebagai parameter
            Excel::import(new MahasiswaImport($angkatan), $file);
    
            // Setelah data diimport, perbarui NIM sesuai urutan
            $this->updateNIMAfterImport($angkatan);
    
            return redirect()->route('mhs')->with('success', "Data mahasiswa angkatan $angkatan berhasil diimport.");
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    protected function updateNIMAfterImport($angkatan)
    {
        // Ambil semua mahasiswa berdasarkan angkatan
        $mahasiswas = Mahasiswa::where('angkatan', $angkatan)->orderBy('id', 'asc')->get();
    
        $nimCounter = 1;
        foreach ($mahasiswas as $mahasiswa) {
            $newNim = $angkatan . str_pad($nimCounter, 4, '0', STR_PAD_LEFT);
            $mahasiswa->nim = $newNim;
            $mahasiswa->save();
            $nimCounter++;
        }
    }
}    
