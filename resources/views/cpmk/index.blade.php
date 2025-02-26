@extends('layouts.backend')

@section('title')
    {{ $judul }}
@endsection

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endsection

@section('js_after')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script src="//cdn.datatables.net/plug-ins/1.11.3/sorting/natural.js"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
@endsection

@section('content')

    <!-- Hero -->
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
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded block-fx-shadow">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ $parent }} <small>List</small></h3>
                <div class="block-options-item">
                    <a href="{{ route('tambahcpmk') }}" class="btn btn-sm btn-primary">Tambah</a>
                </div>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route('cpmk.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Import Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Import</button>
                </form>
               

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            <div class="block-content block-content-full">
                <div class="d-flex flex-row-reverse">
                    <div class="col-lg-4">
                        @if (Session::has('success'))
                            <div class="alert alert-success py-2 mb-0" role="alert">
                                <i class="fa fa-check-circle me-1"></i>{{ Session::get('success') }}
                            </div>
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-warning py-2 mb-0" role="alert">
                                <i class="fa fa-exclamation-triangle me-1"></i>{{ Session::get('error') }}
                            </div>
                        @endif
                    </div>
                </div>
                <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th class="text-center" style="width: 150px;">Kode CPL</th>
                            <th class="text-center">Kode CPMK</th>
                            <th class="text-center">Nama CPMK</th>
                            <th class="text-center">Kode MK</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedCpmk = $cpmk->groupBy('cpl.kode_cpl'); // Grup data berdasarkan CPL
                            $no = 1; // Inisialisasi nomor
                        @endphp
                            @foreach($groupedCpmk as $kodeCpl => $cpmkGroup)
                                @php
                                    $rowspan = $cpmkGroup->count(); // Menghitung jumlah row dalam grup CPL
                                @endphp
                        
                                <!-- Iterasi melalui setiap grup berdasarkan Kode CPL -->
                                @foreach($cpmkGroup as $index => $adm)
                                    <tr>
                                        <!-- Kolom "No" dan "Kode CPL" hanya ditampilkan di baris pertama -->
                                        @if($index === 0)
                                            <td rowspan="{{ $rowspan }}" class="text-center align-middle">{{ $no++ }}</td>
                                            <td rowspan="{{ $rowspan }}" class="text-center align-middle">{{ $kodeCpl }}</td>
                                        @endif
                                        <!-- Informasi Kode CPMK, Nama CPMK, dan Kode MK -->
                                        <!-- Kolom "Kode CPMK", "Nama CPMK", dan "Kode MK" -->
                                        <td class="text-center">{{ $adm->kode_cpmk }}</td>
                                        <td>{{ $adm->nama_cpmk }}</td>
                                        <td>
                                            @if($adm->mataKuliah->isNotEmpty())
                                                @foreach($adm->mataKuliah as $mk)
                                                    {{ $mk->kode }}@if(!$loop->last), @endif
                                                @endforeach
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td class="text-center" style="width: 100px">
                                            <div class="btn-group">
                                                <a type="button" href="{{ Request::url() }}/edit/{{ $adm->id }}"
                                                class="btn btn-secondary btn-sm edit"
                                                title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <button type="button" class="btn btn-secondary btn-sm edit" title="Delete"
                                                    onclick="deleteConfirm('{{ route('cpmk.hapus', $adm->id) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>                        
                    </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
    <!-- END Page Content -->
@endsection
