
@extends('layouts.backend') 

@section('title')
    Detail Mahasiswa
@endsection

@section('content') 


    {{-- Judul Halaman --}} 
    <div class="bg-body-light"> 
        <div class="content content-full"> 
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"> 
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Detail Mahasiswa</h1> 
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb"> 
                    <ol class="breadcrumb"> 
                        <li class="breadcrumb-item">
                            <a href="{{ route('mhs') }}">Mahasiswa</a></li> 
                            <li class="breadcrumb-item active" aria-current="page">Detail</li> 
                        </ol> 
                    </nav> 
                </div> 
            </div> 
        </div>

    {{-- Informasi Mahasiswa --}}
    <div class="content">
        <div class="block block-rounded block-fx-shadow">
            <div class="block-header block-header-default d-flex justify-content-between align-items-center">
                <h3 class="block-title mb-0">Informasi Mahasiswa</h3>
                <button type="button" class="btn btn-sm btn-secondary"
                        onclick="window.location.href='{{ route('mhs') }}'">
                    Kembali
                </button>
            </div>
            <div class="block-content">
                <table class="table table-bordered">
                    <tr>
                        <td><strong>NIM:</strong></td>
                        <td>{{ $mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>Angkatan:</strong></td>
                        <td>{{ $mahasiswa->angkatan }}</td>
                    </tr>
                </table>

                {{-- Tampilkan Mata Kuliah yang Diambil --}}
                <h4 class="mt-4">Mata Kuliah yang Diambil</h4>
                @if($mahasiswa->mataKuliahs->isEmpty())
                    <p>Mahasiswa ini belum mengambil mata kuliah.</p>
                @else
                <table class="table table-bordered"> 
                    <thead> 
                        <tr> 
                            <th>No</th> 
                            <th>Kode</th> 
                            <th>Kelas</th> 
                            <th>Mata Kuliah</th> 
                            <th>SKS</th> 
                            <th>Aksi</th> 
                        </tr> 
                    </thead> 
                    <tbody>
                        @php $totalSKS = 0; @endphp 
                        @foreach($mahasiswa->mataKuliahs as $index => $mataKuliah) 
                            <tr> 
                                <td>{{ $index + 1 }}</td> 
                                <td>{{ $mataKuliah->kode }}</td> 
                                <td>{{ $mataKuliah->kelas }}</td> 
                                <td>{{ $mataKuliah->nama }}</td> 
                                <td>{{ $mataKuliah->sks }}</td> 
                                <td>
                                     <!-- Form Hapus Mata Kuliah -->
                                     <form action="{{ route('mahasiswa.removeMataKuliah', ['mahasiswa' => $mahasiswa->id, 'mataKuliah' => $mataKuliah->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> 
                                        </button>
                                    </form>                                   

                                </td>
                            </tr>
                            
                            @php $totalSKS += $mataKuliah->sks; @endphp <!-- Tambahkan SKS setiap iterasi --> 
                        @endforeach 
                    </tbody>
                </table>
                @if(session('error'))
    <div class="alert alert-danger mt-4">
        {{ session('error') }}
    </div>
@endif

                <p><strong>Total SKS diambil:</strong> {{ $totalSKS }}</p>
                @endif

                {{--Form Tambah Mata Kuliah--}}
                <h5 class="mt-4">Tambah Mata Kuliah</h5>
                    <!-- Tombol untuk membuka modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahMataKuliahModal">
                        Tambah Mata Kuliah
                    </button>

    <div class="modal fade" id="tambahMataKuliahModal" tabindex="-1" aria-labelledby="tambahMataKuliahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMataKuliahModalLabel">Tambah Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th>Kelas</th>
                                    <th>SKS</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mataKuliahs as $index => $mataKuliah)
                                    {{-- Cek apakah mata kuliah sudah diambil oleh mahasiswa --}}
                                    @if(!$mahasiswa->mataKuliahs->contains($mataKuliah->id))
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $mataKuliah->kode }}</td>
                                            <td>{{ $mataKuliah->nama }}</td>
                                            <td>{{ $mataKuliah->kelas }}</td>
                                            <td>{{ $mataKuliah->sks }}</td>
                                            <td>
                                                <!-- Tombol Tambah -->
                                                <form action="{{ route('mahasiswa.addMataKuliah', ['id' => $mahasiswa->id]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">
                                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-plus-square"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
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
