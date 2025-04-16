@extends('layouts.backend')

@section('title', 'Detail Ketercapaian Mahasiswa')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Ketercapaian Mahasiswa</h1>
        <form method="GET" action="{{ route('ketercapaian.show', $mahasiswa->id) }}" class="mb-3">
            <label for="semester">Semester:</label>
            <select name="semester" id="semester" class="form-control w-auto d-inline mx-2">
                <option value="">-- Semua Semester --</option>
                @foreach($semesters as $smt)
                    <option value="{{ $smt }}" {{ request('semester') == $smt ? 'selected' : '' }}>Semester {{ $smt }}</option>
                @endforeach
            </select>

        </form>
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
            <table class="table table-bordered">
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
                            $totalNilai = $rentang['total_nilai'] ?? 0; // Pastikan total nilai diambil dengan aman
                            $grade = $rentang['grade'] ?? '-';
                        @endphp
                        <tr>
                            <td rowspan="{{ count($nilaiItems) }}">{{ $mataKuliah->kode ?? 'Kode MK Tidak Ditemukan' }}</td>
                            <td rowspan="{{ count($nilaiItems) }}">{{ $mataKuliah->nama ?? '-' }}</td>
            
                            @foreach ($nilaiItems as $index => $item)
                                @if ($index == 0)
                                    <td>{{ $item->rumusanAkhirMk->kd_cpl ?? '-' }}</td>
                                    <td>{{ $item->rumusanAkhirMk->kd_cpmk ?? '-' }}</td>
                                    <td>{{ $item->nilai }}</td> <!-- Nilai mahasiswa -->
                                    <td rowspan="{{ count($nilaiItems) }}">{{ $totalNilai }}</td> <!-- Total skor -->
                                    <td rowspan="{{ count($nilaiItems) }}">{{ $grade }}</td> <!-- Rentang nilai (grade) -->
                                @else
                                    <tr>
                                        <td>{{ $item->rumusanAkhirMk->kd_cpl ?? '-' }}</td>
                                        <td>{{ $item->rumusanAkhirMk->kd_cpmk ?? '-' }}</td>
                                        <td>{{ $item->nilai }}</td> <!-- Nilai mahasiswa -->
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
                        <th>Total Skor Maksimal</th> <!-- Tambahkan kolom ini -->
                        <th>Persentase Ketercapaian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($capaianCpl as $item)
                        <tr>
                            <td>{{ $item['kode_cpl'] }}</td>
                            <td>{{ $item['total_nilai'] }}</td>
                            <td>{{ $item['total_skor_maksimal'] ?? '-' }}</td> <!-- Tampilkan total skor maksimal -->
                            <td>{{ $item['persentase'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.getElementById('semester').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
    
    
@endsection
