<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\MataKuliah;
use App\Models\DosenAdmin;
use App\Imports\MataKuliahImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Routing\Controller;

class MataKuliahController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $user = auth()->user();

        // Cek apakah user adalah dosen
        if ($user->role == 'dosen') {
            // Ambil hanya mata kuliah yang diampu oleh dosen yang sedang login
            $tampil = MataKuliah::where('dosen_pengampu_1', $user->id)
                ->orWhere('dosen_pengampu_2', $user->id)
                ->get();
        } else {
            // Jika admin, tampilkan semua mata kuliah
            $tampil = MataKuliah::all();
        }

        return view('matakuliah.index', [
            'matakuliah' => $tampil,
            'judul' => 'Kelola Mata Kuliah',
            'parent' => 'Mata Kuliah',
        ]);
    }


    public function tambahindex()
    {
        // Mengambil data dosen dari tabel dosen_admins
        $dosenAdmins = DosenAdmin::all();

        // Nilai tetap
        $judul = 'Tambah Mata Kuliah';
        $judulform = 'Form Tambah Mata Kuliah';
        $parent = 'Mata Kuliah';
        $subparent = 'Tambah';

        // Mengirimkan variabel ke view
        return view('matakuliah.tambah', [
            'judul' => $judul,
            'judulform' => $judulform,
            'parent' => $parent,
            'subparent' => $subparent,
            'dosen_admins' => $dosenAdmins, // Pastikan variabel yang dikirim adalah $dosenAdmins
        ]);
    }

    public function editindex(int $id)
    {
        // Nilai tetap
        $judul = 'Edit Mata Kuliah';
        $parent = 'Mata Kuliah';
        $subparent = 'Edit';

        $tampil = MataKuliah::find($id);
        $dosenAdmins = DosenAdmin::all();

        return view('matakuliah.edit', [
            'judul' => $judul,
            'parent' => $parent,
            'subparent' => $subparent,
            'matakuliah' => $tampil,
            'dosen_admins' => $dosenAdmins,
        ]);
    }

    public function tambah(Request $request)
    {
        $rules = [
            'kode' => 'required|string',  // Validasi kode mata kuliah
            'nama' => 'required|string',  // Validasi nama mata kuliah
            'kelas' => 'nullable|string', // Kelas bersifat opsional
            'sks' => 'required|integer',  // Validasi SKS
            'semester' => 'required|integer', // Validasi semester
            'dosen_pengampu_1' => 'nullable|exists:dosen_admins,id', // Pastikan dosen pengampu 1 ada di tabel dosen_admins
            'dosen_pengampu_2' => 'nullable|exists:dosen_admins,id', // Pastikan dosen pengampu 2 ada di tabel dosen_admins
        ];

        // Melakukan validasi
        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembalikan ke form dengan pesan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $mk = new MataKuliah();
        $mk->kode = $request->input('kode');
        $mk->nama = $request->input('nama');
        $mk->kelas = $request->input('kelas');
        $mk->sks = $request->input('sks');
        $mk->semester = $request->input('semester');
        $mk->dosen_pengampu_1 = $request->input('dosen_pengampu_1');
        $mk->dosen_pengampu_2 = $request->input('dosen_pengampu_2');

        // Menyimpan data mata kuliah ke database
        $mk->save();

        // Mengarahkan kembali dengan pesan sukses
        return back()->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function edit(Request $request, int $id)
    {
        $mk = MataKuliah::firstWhere('id', $id);

        $rules = [
            'kode' => 'required|string',  // Validasi kode mata kuliah
            'nama' => 'required|string',  // Validasi nama mata kuliah
            'kelas' => 'nullable|string', // Kelas bersifat opsional
            'sks' => 'required|integer',  // Validasi SKS
            'semester' => 'required|integer', // Validasi semester
            'dosen_pengampu_1' => 'nullable|exists:dosen_admins,id',
            'dosen_pengampu_2' => 'nullable|exists:dosen_admins,id',
        ];

        // Validasi data yang dimasukkan
        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembali ke form dengan pesan error dan input yang lama
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }


        // Mengupdate data mata kuliah
        $mk->kode = $request->input('kode');
        $mk->nama = $request->input('nama');
        $mk->kelas = $request->input('kelas');
        $mk->sks = $request->input('sks');
        $mk->semester = $request->input('semester');
        $mk->dosen_pengampu_1 = $request->input('dosen_pengampu_1');
        $mk->dosen_pengampu_2 = $request->input('dosen_pengampu_2');

        // Menyimpan perubahan data ke database
        $mk->save();

        // Mengarahkan kembali dengan pesan sukses
        return back()->with('success', 'Data Berhasil Diubah!');
    }
    public function hapus(int $id)
    {
        MataKuliah::find($id)->delete();

        return redirect()->route('mk');
    }
    public function fetchMataKuliah(Request $request)
    {
        // Ambil semua data mata kuliah
        $mataKuliah = DB::table('matakuliah')
            ->select('id', 'kode', 'nama')
            ->get();

        // Return data sebagai JSON
        return response()->json($mataKuliah);
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
            Excel::import(new MataKuliahImport, $file);
            return redirect()->route('mk')->with('success', 'Data berhasil diimport.');
        } catch (\Exception $e) {
            // Log error dan tangani kesalahan
            \Log::error('Error importing file: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat mengimpor file.']);
        }
    }
}

