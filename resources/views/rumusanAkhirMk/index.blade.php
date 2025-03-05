@extends('layouts.backend')

@section('title')
    Rumusan Akhir MK
@endsection

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2-container {
            display: block;
        }
    </style>
@endsection

@section('js_after')
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
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
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
    <script>Dashmix.helpersOnLoad(['jq-select2']);</script>
@endsection

@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Rumusan Akhir MK</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"></li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Tambah Data -->
    <div class="block block-rounded block-fx-shadow">
        <div class="block-header block-header-default">
            <h3 class="block-title">Rumusan Akhir MK <small>List</small></h3>
            <div class="block-options">
                <a href="{{ route('tambah-rumusan_akhir_mk') }}" class="btn btn-sm btn-primary">Tambah</a>
            </div>
        </div>

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

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center table-vcenter js-datatable-bottons">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th class="text-center" style="width: 100px;">Kode MK</th>
                            <th class="text-center">Mata Kuliah</th>
                            <th class="text-center">CPL</th>
                            <th class="text-center">CPMK</th>
                            <th class="text-center">Skor Maks</th>
                            <th class="text-center">Total</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($rumusanAkhirMkGrouped as $kd_mk => $group)
                        @foreach($group as $key => $rumusan)
                            @php
                                $cpls = explode(',', $rumusan->kd_cpl);
                                $cpmks = explode(',', $rumusan->kd_cpmk);
                                $skorMaksimals = explode(',', $rumusan->skor_maksimal);
                                $rowspan = count($cpmks);
                            @endphp
                            
                            @foreach ($cpmks as $index => $cpmk)
                                <tr>
                                    @if($index === 0)
                                        <td rowspan="{{ $rowspan }}" class="text-center align-middle">{{ $no++ }}</td>
                                        <td rowspan="{{ $rowspan }}" class="text-center align-middle">{{ $rumusan->mataKuliah->kode ?? 'N/A' }}</td>
                                        <td rowspan="{{ $rowspan }}" class="text-center align-middle">{{ $rumusan->mataKuliah->nama ?? 'N/A' }}</td>
                                        <td rowspan="{{ $rowspan }}" class="text-center align-middle">{{ implode(', ', $cpls) }}</td>
                                    @endif

                                    <!-- Tampilkan CPMK dan Skor Maksimal sesuai dengan masing-masing CPMK -->
                                    <td class="text-center">{{ $cpmk }}</td>
                                    <td class="text-center">{{ $skorMaksimals[$index] ?? '0' }}</td> <!-- Perbaikan di sini -->

                                    @if ($index === 0)
                                        <td rowspan="{{ $rowspan }}" class="text-center align-middle">{{ array_sum($skorMaksimals) }}</td>
                                        <td rowspan="{{ $rowspan }}" class="text-center align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('rumusanAkhirMk.edit', $rumusan->id) }}" class="btn btn-secondary btn-sm edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <form action="{{ route('rumusan_akhir_mk.destroy', $rumusan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-secondary btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>                                                  
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection