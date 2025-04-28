@extends('layouts.backend')

@section('title')
    Rumusan Akhir MK
@endsection

@section('css_before')
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

<div class="content">
    <div class="block block-rounded block-fx-shadow">
        <div class="block-header block-header-default">
            <h3 class="block-title">Rumusan Akhir MK <small>List</small></h3>
            <div class="block-options">
                <a href="{{ route('tambah-rumusan_akhir_mk') }}" class="btn btn-sm btn-primary">Tambah</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="font-weight: bold;">No.</th>
                            <th style="font-weight: bold;">Mata Kuliah</th>
                            <th style="font-weight: bold;">CPL</th>
                            <th style="font-weight: bold;">CPMK</th>
                            <th style="font-weight: bold;">Skor Maksimal</th>
                            <th style="font-weight: bold;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $noMataKuliah = 1; @endphp
                        @foreach ($grouped as $mataKuliahNama => $cplGroups)
                            @php
                                $mataKuliahRowspan = $cplGroups->flatten()->count();
                                $totalSkor = $cplGroups->flatten()->sum('skor_maksimal');
                                $firstMataKuliah = true;
                                $firstMataKuliahTotal = true;
                            @endphp

                            @foreach ($cplGroups as $cplKode => $items)
                                @php
                                    $cplRowspan = $items->count();
                                    $firstCpl = true;
                                    $cpl = \App\Models\Cpl::find($items->first()->kd_cpl);
                                @endphp

                                @foreach ($items as $item)
                                    @php
                                        $cpmk = \App\Models\Cpmk::find($item->kd_cpmk);
                                    @endphp

                                    <tr>
                                        @if ($firstMataKuliah)
                                            <td rowspan="{{ $mataKuliahRowspan }}">{{ $noMataKuliah }}</td>
                                            <td rowspan="{{ $mataKuliahRowspan }}">{{ $mataKuliahNama }}</td>
                                            @php
                                                $firstMataKuliah = false;
                                            @endphp
                                        @endif

                                        @if ($firstCpl)
                                            <td rowspan="{{ $cplRowspan }}">
                                                {{ $cpl->kode_cpl ?? '-' }}
                                            </td>
                                            @php
                                                $firstCpl = false;
                                            @endphp
                                        @endif

                                        <td>
                                            {{ $cpmk->kode_cpmk ?? '-' }}
                                        </td>

                                        <td>{{ $item->skor_maksimal }}</td>

                                        @if ($loop->first && $firstMataKuliahTotal)
                                            <td rowspan="{{ $mataKuliahRowspan }}">{{ $totalSkor }}</td>
                                            @php $firstMataKuliahTotal = false; @endphp
                                        @endif
                                    </tr>
                                @endforeach
                            @endforeach
                            @php $noMataKuliah++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
