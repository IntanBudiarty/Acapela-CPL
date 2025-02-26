{{-- resources/views/mahasiswa/detail.blade.php --}}

@extends('layouts.backend')  {{-- Pastikan Anda menggunakan layout yang benar sesuai aplikasi Anda. --}}

@section('title')
    Pemetaan CPL
@endsection

@section('content') 
    {{-- Judul Halaman --}} 
    <div class="bg-body-light"> 
        <div class="content content-full"> 
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"> 
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Detail CPL</h1> 
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb"> 
                    <ol class="breadcrumb"> 
                        <li class="breadcrumb-item">
                            <a href="{{ route('cpl') }}">Pemetaan CPL-CPMK-MK</a></li> 
                            <li class="breadcrumb-item active" aria-current="page">Detail</li> 
                        </ol> 
                    </nav> 
                </div> 
            </div> 
        </div>

    {{-- Informasi Mahasiswa --}}
    <div class="content">
        <div class="block block-rounded block-fx-shadow">
            <div class="block-header block-header-default">
                <h3 class="block-title">Informasi CPL</h3>
            </div>
            <div class="block-content">
                <table class="table table-bordered">
                    <tr>
                        <td><strong>Kode CPl:</strong></td>
                        <td>{{ $cpl->kode_cpl }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $cpl->nama_cpl }}</td>
                    </tr>
                </table>

                {{-- Tampilkan Mata Kuliah yang Diambil --}}
                <h4 class="mt-4">Pemenuhan CPMK dan MK</h4>
                    <p>CPMK dan MK yang harus di penuhi</p>
                <table class="table table-bordered"> 
                    <thead> 
                        <tr> 
                            <th>No</th> 
                            <th>Kode CPMK</th> 
                            <th>Nama CPMK</th> 
                            <th>Kode MK</th> 
                        </tr> 
                    </thead> 
                    <tbody>
                        @foreach($cpl as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kode_cpl }}</td>
                                <td>{{ $item->nama_cpl }}</td>                                

                                </td>
                            </tr>
                            
                        @endforeach 
                    </tbody>

                {{--Form Tambah Mata Kuliah--}}
                <h5 class="mt-4">Tambah </h5>
                    <!-- Tombol untuk membuka modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahMataKuliahModal">
                        Tambah Pemetan CPMK dan MK
                    </button>

                    <!-- Modal -->
                  {{-- Modal Tambah Mata Kuliah --}}
    <div class="modal fade" id="tambahMataKuliahModal" tabindex="-1" aria-labelledby="tambahMataKuliahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMataKuliahModalLabel">Tambah Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tabel Mata Kuliah -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode CPMK</th>
                                    <th>Nama CPMK</th>
                                    <th>Kode MK</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cpmk as $adm)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $adm->kode_cpmk }}</td>
                                    <td>{{ $adm->nama_cpmk }}</td>
                                    <td>
                                                
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

@endsection
