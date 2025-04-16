@extends('layouts.backend')

@section('title', 'Detail Ketercapaian Mahasiswa')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Ketercapaian Mahasiswa</h1>
<<<<<<< HEAD
        
        <div class="d-flex justify-content-between align-items-end mb-3">
            <form method="GET" action="{{ route('ketercapaian.show', $mahasiswa->id) }}" class="d-flex align-items-end gap-2">
                <div>
                    <label for="semester">Pilih Semester:</label>
                    <select name="semester" id="semester" class="form-control">
                        <option value="">-- Semua Semester --</option>
                        @foreach($semesters as $smt)
                            <option value="{{ $smt }}" {{ request('semester') == $smt ? 'selected' : '' }}>Semester {{ $smt }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>

            <!-- Container untuk tombol ekspor -->
            <div id="exportButtons"></div>
        </div>
=======
        <form method="GET" action="{{ route('ketercapaian.show', $mahasiswa->id) }}" class="mb-3">
            <label for="semester">Semester:</label>
            <select name="semester" id="semester" class="form-control w-auto d-inline mx-2">
                <option value="">-- Semua Semester --</option>
                @foreach($semesters as $smt)
                    <option value="{{ $smt }}" {{ request('semester') == $smt ? 'selected' : '' }}>Semester {{ $smt }}</option>
                @endforeach
            </select>

        </form>
>>>>>>> 5c2f0a009250804e08487ef8bb7d635dd0daef8a
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-fx-shadow">
        <div class="block-content">
            <table class="table table-bordered">
                <tr>
                    <td><strong>Nama Mahasiswa:</strong></td>
                    <td>{{ $mahasiswa->nama }}</td>
                </tr>
                <tr>
                    <td><strong>NIM: </strong></td>
                    <td>{{ $mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <td><strong>Angkatan:</strong></td>
                    <td>{{ $mahasiswa->angkatan }}</td>
                </tr>
            </table>

            <h4>Ketercapaian</h4>
            <table class="table table-bordered js-dataTable-buttons">
                <thead>
                    <tr>
                        <th>Kode MK</th>
                        <th>Nama MK</th>
                        <th>Kode CPL</th>
                        <th>Kode CPMK</th>
                        <th>Nilai</th>
                        <th>Total Skor</th>
                        <th>Nilai Huruf</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ketercapaian as $mataKuliahId => $nilaiItems)
                        @php
                            $mataKuliah = $nilaiItems->first()->mataKuliah;
                            $rentang = $rentangNilai[$mataKuliahId] ?? null;
                            $totalNilai = $rentang['total_nilai'] ?? 0;
                            $grade = $rentang['grade'] ?? '-';
                        @endphp
                        <tr>
                            <td rowspan="{{ count($nilaiItems) }}">{{ $mataKuliah->kode ?? 'Kode MK Tidak Ditemukan' }}</td>
                            <td rowspan="{{ count($nilaiItems) }}">{{ $mataKuliah->nama ?? '-' }}</td>

                            @foreach ($nilaiItems as $index => $item)
                                @if ($index == 0)
                                    <td>{{ $item->rumusanAkhirMk->kd_cpl ?? '-' }}</td>
                                    <td>{{ $item->rumusanAkhirMk->kd_cpmk ?? '-' }}</td>
                                    <td>{{ $item->nilai }}</td>
                                    <td rowspan="{{ count($nilaiItems) }}">{{ $totalNilai }}</td>
                                    <td rowspan="{{ count($nilaiItems) }}">{{ $grade }}</td>
                                @else
                                    <tr>
                                        <td>{{ $item->rumusanAkhirMk->kd_cpl ?? '-' }}</td>
                                        <td>{{ $item->rumusanAkhirMk->kd_cpmk ?? '-' }}</td>
                                        <td>{{ $item->nilai }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="block block-rounded block-fx-shadow mt-4">
        <div class="block-content">
            <h4>Capaian CPL</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode CPL</th>
                        <th>Total Nilai</th>
                        <th>Total Skor Maksimal</th>
                        <th>Persentase Ketercapaian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($capaianCpl as $item)
                        <tr>
                            <td>{{ $item['kode_cpl'] }}</td>
                            <td>{{ $item['total_nilai'] }}</td>
                            <td>{{ $item['total_skor_maksimal'] ?? '-' }}</td>
                            <td>{{ $item['persentase'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
<<<<<<< HEAD
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
        document.getElementById('semester').addEventListener('change', function () {
            this.form.submit();
        });

        $(document).ready(function () {
            let table = $('table.js-dataTable-buttons').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'pdf', 'print'],
                paging: false,
                ordering: false,
                info: false
            });

            table.buttons().container().appendTo('#exportButtons');
        });
    </script>
=======
    <script>
        document.getElementById('semester').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
    
    
>>>>>>> 5c2f0a009250804e08487ef8bb7d635dd0daef8a
@endsection
