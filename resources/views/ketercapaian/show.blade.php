@extends('layouts.backend')

@section('title', 'Detail Ketercapaian Mahasiswa')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Ketercapaian Mahasiswa</h1>
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

                <h4>Ketercapaian</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode MK</th>
                            <th>Nama MK</th>
                            <th>Kode CPL</th>
                            <th>Kode CPMK</th>
                            <th>Nilai</th>
                            <th>Total Nilai</th>
                            <th>Akumulasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ketercapaian as $mataKuliahId => $nilaiItems)
                            @php
                                $mataKuliah = $nilaiItems->first()->mataKuliah;
                                $rentang = $rentangNilai[$mataKuliahId];
                            @endphp
                            <tr>
                                <td rowspan="{{ count($nilaiItems) }}">{{ $mataKuliah->kode ?? 'Kode MK Tidak Ditemukan' }}</td>
                                <td rowspan="{{ count($nilaiItems) }}">{{ $mataKuliah->nama ?? '-' }}</td>

                                @foreach ($nilaiItems as $index => $item)
                                    @if ($index == 0)
                                        <td>{{ $item->rumusanAkhirMk->kd_cpl ?? '-' }}</td>
                                        <td>{{ $item->rumusanAkhirMk->kd_cpmk ?? '-' }}</td>
                                        <td>{{ $item->nilai }}</td> <!-- Nilai mahasiswa -->
                                        <td rowspan="{{ count($nilaiItems) }}">{{ $rentang['total_nilai'] }}</td> <!-- Total nilai -->
                                        <td rowspan="{{ count($nilaiItems) }}">{{ $rentang['grade'] }}</td> <!-- Rentang nilai (grade) -->
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
                        <th>Nama CPL</th>
                        <th>Total Nilai</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach ($capaianCpl as $item)
                        <tr>
                            <td>{{ $item['kode_cpl'] }}</td>
                            <td>{{ $item['nama_cpl'] }}</td>
                            <td>{{ $item['total_nilai'] }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>               
        </div>
    </div>
</div>
@endsection
   
