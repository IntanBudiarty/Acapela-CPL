@extends('layouts.backend')

@section('title')
    Mata Kuliah yang Diampu: {{ $dosen->nama }}
@endsection

@section('content')
    <div class="content">
        <h1>Mata Kuliah yang Diampu</h1>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>SKS</th>
                    <th>Semester</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($semuaMataKuliah as $index => $mataKuliah)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mataKuliah->kode }}</td>
                        <td>{{ $mataKuliah->nama }}</td>
                        <td>{{ $mataKuliah->kelas }}</td>
                        <td>{{ $mataKuliah->sks }}</td>
                        <td>{{ $mataKuliah->semester }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada mata kuliah yang diampu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
