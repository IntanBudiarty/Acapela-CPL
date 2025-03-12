@extends('layouts.backend')

@section('title')
    Nilai Mahasiswa
@endsection

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <h1 class="fs-3 fw-semibold my-2 my-sm-3">Nilai Mahasiswa</h1>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Data Mata Kuliah yang Diampu</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mataKuliah as $mk)
                                <tr>
                                    <td>{{ $mk->kode }}</td>
                                    <td>{{ $mk->nama }}</td>
                                    <td>
                                        <a href="{{ route('nilai.show', $mk->id) }}" class="btn btn-primary btn-sm">
                                            Lihat Nilai
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Tidak ada mata kuliah yang diampu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
