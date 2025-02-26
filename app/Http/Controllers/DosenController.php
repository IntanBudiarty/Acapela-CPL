<?php

namespace App\Http\Controllers;

use App\Models\DosenAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class DosenController extends Controller
{
    public function index()
    {
        // Nilai tetap
        $judul = 'Kelola Dosen';
        $parent = 'Dosen';

        $tampil = DosenAdmin::whereHas('user', function ($query) {
            return $query->whereRaw("status IN ('Dosen')");
        })->get();

        return view('dosen.index', [
            'dosen' => $tampil,
            'judul' => $judul,
            'parent' => $parent,
        ]);
    }

    public function tambahindex()
    {
        // Nilai tetap
        $judul = 'Tambah Dosen';
        $judulform = 'Form Tambah Dosen';
        $parent = 'Dosen';
        $subparent = 'Tambah';

        return view('dosen.tambah', [
            'judul' => $judul,
            'judulform' => $judulform,
            'parent' => $parent,
            'subparent' => $subparent,
        ]);
    }

    public function editindex(int $id)
    {
        // Nilai tetap
        $judul = 'Edit Dosen';
        $parent = 'Dosen';
        $subparent = 'Edit';

        $user = DosenAdmin::with('user')->find($id);

        return view('dosen.edit', [
            'judul' => $judul,
            'parent' => $parent,
            'subparent' => $subparent,
            'dosen' => $user,
        ]);
    }

    public function tambah(Request $request)
    {
        $rules = [
            'nip' => 'required|integer|unique:dosen_admins',
            'nama' => 'required|string',
            'username' => 'required|string|unique:users',
            'password' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $user = new User;
        $user->username = $request->input('username');
        $user->nip = $request->input('nip');
        $user->status = 'Dosen';
        $user->password = bcrypt($request->input('password'));
        $user->save();
        $user->assignRole('dosen');

        $dosenadmin = new DosenAdmin;
        $dosenadmin->nip = $request->input('nip');
        $dosenadmin->nama = $request->input('nama');
        $dosenadmin->user()->associate($user);
        $dosenadmin->save();

        return back()->with('success', 'Data Berhasil Ditambahkan!.');
    }

    public function edit(Request $request, int $id)
    {
        $data = DosenAdmin::with('user')->firstWhere('id', $id);

        $rules = [
            'nip' => 'required|integer|unique:dosen_admins,nip,' . $data->id,
            'nama' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $data->user->id,
            'password' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $data->nip = $request->input('nip');
        $data->nama = $request->input('nama');
        $data->user->username = $request->input('username');
        if ($data->user->password !== $request->input('password')) {
            $data->user->password = bcrypt($request->input('password'));
        }
        $data->user->save();
        $data->save();
        User::find($id)->assignRole('dosen');

        return back()->with('success', 'Data Berhasil Diubah!.');
    }

    public function hapus(int $id)
    {
        DosenAdmin::where('user_id', $id)->delete();
        User::find($id)->delete();

        return redirect()->route('dosen');
    }
}
