@extends('layouts.backend')

@section('title')
    Rumusan Akhir CPL
@endsection

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js_after')
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#cplTable').DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            }).buttons().container().appendTo('#cplTable_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Rumusan Akhir CPL</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Rumusan Akhir CPL</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="block-header block-header-default">
        <h3 class="block-title">Rumusan Akhir CPL <small>List</small></h3>
        <div class="block-options-item">
        </div>
    </div>
    <div class="block-content block-content-full">
        <table id="cplTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode CPL</th>
                    <th>Kode MK</th>
                    <th>Mata Kuliah</th>
                    <th>Kode CPMK</th>
                    <th>Skor Maksimal</th>
                    <th>Total Skor</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($groupedData as $kd_cpl => $group)
                    @php $rowCount = $group['records']->count(); @endphp
                    @foreach ($group['records'] as $index => $data)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <!-- Menampilkan Kode CPL -->
                            <td>{{ \App\Models\Cpl::where('kode_cpl', $data->kd_cpl)->first()->kode_cpl ?? 'Data Tidak Tersedia' }}</td>
                            <td>{{ $data->rumusanAkhirMk->mataKuliah->kode ?? 'Data Tidak Tersedia' }}</td>
                            <td>{{ $data->rumusanAkhirMk->mataKuliah->nama ?? 'Data Tidak Tersedia' }}</td>
                            <!-- Menampilkan Kode CPMK -->
                            <td>{{ \App\Models\Cpmk::find($data->cpmk)->kode_cpmk ?? 'Data Tidak Tersedia' }}</td>
                            <td>{{ $data->skor_maksimal }}</td>
                            @if ($index === 0)
                                <td rowspan="{{ $rowCount }}">{{ $group['total_skor'] }}</td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- END Page Content -->
@endsection
