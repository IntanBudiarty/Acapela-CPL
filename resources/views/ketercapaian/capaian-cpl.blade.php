@extends('layouts.backend')
@section('content')
<div class="card">
    <div class="card-body">

        {{-- Kop Surat --}}
        <div class="row align-items-center mb-4">
            <div class="col-2 text-center">
                <img src="{{ asset('media/photos/ketercapaian/Logo-unib.png') }}" alt="Logo UNIB" width="100" height="100">
            </div>
            <div class="col-10 text-right">
                <h4 style="margin:0;">UNIVERSITAS BENGKULU</h4>
                <h6 style="margin:0;">FAKULTAS TEKNIK</h6>
                <h6 style="margin:0;">PROGRAM STUDI SISTEM INFORMASI</h6>
                {{-- <hr style="border: 1px solid #000;"> --}}
            </div>
        </div>

        {{-- Data Mahasiswa --}}
        <div class="row">
            <div class="col">
                <table class="table table-bordered" style="font-size: 12px">
                    <tr>
                        <td><strong>Nama Mahasiswa:</strong></td>
                        <td>{{ $mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIM:</strong></td>
                        <td>{{ $mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <td><strong>Angkatan:</strong></td>
                        <td>{{ $mahasiswa->angkatan }}</td>
                    </tr>
                </table>
            </div>
            <div class="col">
                <table class="table table-bordered" style="font-size: 12px">
                    <tr>
                        <td><strong>Fakultas:</strong></td>
                        <td>TEKNIK</td>
                    </tr>
                    <tr>
                        <td><strong>Program Studi:</strong></td>
                        <td>SISTEM INFORMASI</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Tabel CPL --}}
        <div class="mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode CPL</th>
                        <th>Nama CPL</th>
                        <th>Total Nilai</th>
                        <th>Total Skor Maksimal</th>
                        <th>Persentase (%)</th>
                        <th>Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($capaianCpl as $cpl)
                        <tr>
                            <td style="font-size: 12px">{{ $cpl['kode_cpl'] }}</td>
                            <td style="font-size: 12px">{{ $cpl['nama_cpl'] }}</td>
                            <td style="font-size: 12px">{{ $cpl['total_nilai'] }}</td>
                            <td style="font-size: 12px">{{ $cpl['total_skor_maksimal'] }}</td>
                            <td style="font-size: 12px">{{ $cpl['persentase'] }}%</td>
                            <td style="font-size: 12px">{{ $cpl['predikat'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-left: 80px; text-align: end; font-size: 12px;">
            <p>Mengetahui,</p>
            <p>Kepala Program Studi Sistem Informasi</p>
            <br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">Dr.Endina Putri Purwandari S.T.,M.KOM</p>
        </div>
    </div>
</div>
@endsection
