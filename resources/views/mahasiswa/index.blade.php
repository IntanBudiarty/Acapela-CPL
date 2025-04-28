@extends('layouts.backend')

@section('title')
    {{ $judul }}
@endsection

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endsection

@section('js_after')
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Sembunyikan semua tabel mahasiswa saat pertama kali dimuat
            $(".table-mahasiswa").hide();

            // Tambahkan event klik pada judul angkatan untuk toggle tampil/sembunyi tabel
            $(".angkatan-title").click(function() {
                var angkatan = $(this).data("angkatan");
                $("#table-" + angkatan).slideToggle();
            });
        });
    </script>
@endsection

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ $judul }}</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">{{ $parent }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded block-fx-shadow">
            <div class="block-header block-header-default">
                <h3 class="block-title">Mahasiswa <small>List</small></h3>
                <p class="fs-7 fw-lighter text-danger">*untuk menampilkan angkatan baru ,<br> tambahkan satu mahasiswa untuk angkatan baru tersebut.</p>
                <div class="block-options">
                    <a href="{{ route('tambahmhs') }}" class="btn btn-sm btn-primary">Tambah</a>
                </div>
            </div>
            <div class="block-content block-content-full">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form Import Excel -->
                <div class="block-content block-content-full">
                    <form action="{{ route('mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="angkatan">Pilih Angkatan</label>
                            <select name="angkatan" class="form-control" required>
                                <option value="">-- Pilih Angkatan --</option>
                                @foreach ($mahasiswa as $angkatan => $listMahasiswa)
                                    <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="file">Import Excel</label>
                            <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2">Import</button>
                    </form>
                </div>

                <div class="table-responsive">
                    @foreach ($mahasiswa as $angkatan => $listMahasiswa)
                        <!-- Judul Angkatan yang dapat diklik -->
                        <h4 class="mt-4 angkatan-title" data-angkatan="{{ $angkatan }}" style="cursor: pointer; color: blue;">
                            Angkatan {{ $angkatan }} <i class="fas fa-chevron-down"></i>
                        </h4>

                        <!-- Tabel Mahasiswa (default disembunyikan) -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons table-mahasiswa" id="table-{{ $angkatan }}">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">No.</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Angkatan</th>
                                    <th>Kelas</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listMahasiswa as $index => $adm)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $adm->nim }}</td>
                                        <td>{{ $adm->nama }}</td>
                                        <td>{{ $adm->angkatan }}</td>
                                        <td>{{ $adm->kelas}}</td>
                                        <td class="text-center" style="width: 100px">
                                            <div class="btn-group">
                                                <a href="{{ Request::url() }}/edit/{{ $adm->id }}" class="btn btn-secondary btn-sm" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <button type="button" class="btn btn-secondary btn-sm" title="Delete"
                                                    onclick="deleteConfirm('{{ Request::url() }}/hapus/{{ $adm->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <a href="{{ route('mahasiswa.mataKuliah', $adm->id) }}" class="btn btn-primary btn-sm" title="Lihat detail">
                                                    <i class="fa fa-book me-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
