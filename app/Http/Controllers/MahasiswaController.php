<?php

namespace App\Http\Controllers;

use Validator;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use App\Imports\MahasiswaImport;
use Illuminate\Routing\Controller;
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

        // // Tentukan NIM baru, jika belum ada mahasiswa dengan angkatan tersebut, mulai dari 1
        // $newNim = $lastMahasiswa ? (int)substr($lastMahasiswa->nim, -4) + 1 : 1;
        // $newNim = str_pad($newNim, 4, '0', STR_PAD_LEFT); // Pastikan NIM selalu 4 digit, seperti 0001, 0002, dll
        // $newNim = $angkatan . $newNim; // Gabungkan angkatan dan NIM

        // Simpan data mahasiswa baru
        $mhs = new Mahasiswa();
        $mhs->nim = $request->input('nim');
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
        $mataKuliahId = $request->mata_kuliah_id;

        // Ambil semester langsung dari mata_kuliah
        $mataKuliah = MataKuliah::findOrFail($mataKuliahId);
        $semester = $mataKuliah->semester;

        $mahasiswaKlik = Mahasiswa::findOrFail($mahasiswaId);

        // Cek apakah mahasiswa sudah mengambil MK ini
        if ($mahasiswaKlik->mataKuliahs()->where('mata_kuliah_id', $mataKuliahId)->exists()) {
            return back()->with('error', 'Mata kuliah telah diambil oleh mahasiswa ini.');
        }

        // Tambahkan ke mahasiswa yang mengklik
        $mahasiswaKlik->mataKuliahs()->attach($mataKuliahId, ['semester' => $semester]);

        // Tambahkan juga ke mahasiswa lain yang belum ambil MK ini
        $mahasiswaLain = Mahasiswa::where('id', '!=', $mahasiswaId)->get();
        $jumlahDitambahkan = 0;

        foreach ($mahasiswaLain as $mhs) {
            if (!$mhs->mataKuliahs()->where('mata_kuliah_id', $mataKuliahId)->exists()) {
                $mhs->mataKuliahs()->attach($mataKuliahId, ['semester' => $semester]);
                $jumlahDitambahkan++;
            }
        }

        return back()->with('success', "Mata kuliah berhasil ditambahkan ke {$jumlahDitambahkan} mahasiswa lain.");
    }

    public function removeMataKuliah($mahasiswaId, $mataKuliahId)
    {
        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
        $mataKuliah = MataKuliah::findOrFail($mataKuliahId);

        // Hapus nilai yang terkait dengan mahasiswa dan mata kuliah ini
        \App\Models\Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('mata_kuliah_id', $mataKuliahId)
            ->delete();

        // Hapus relasi dari tabel pivot
        $mahasiswa->mataKuliahs()->detach($mataKuliahId);

        return redirect()->route('mahasiswa.detail', $mahasiswa->id)
            ->with('success', 'Mata Kuliah dan nilai terkait berhasil dihapus!');
    }

    public function showDetail($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mataKuliah = $mahasiswa->mataKuliah; 
        return view('mahasiswa.detail', compact('mahasiswa', 'mataKuliah'));
    }
  
    public function detail($id)
    {
       
        $mahasiswa = Mahasiswa::with(['mataKuliahs' => function($query) {
            $query->withPivot('semester');
        }])->find($id);
        
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

            // // Setelah data diimport, perbarui NIM sesuai urutan
            // $this->updateNIMAfterImport($angkatan);

            return redirect()->route('mhs')->with('success', "Data mahasiswa angkatan $angkatan berhasil diimport.");
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    
}    
