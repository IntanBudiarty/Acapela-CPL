@extends('layouts.backend')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <table class="table table-bordered">
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
                <table class="table table-bordered">
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
        </div> {{-- end row --}}
        
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
                            <td>{{ $cpl['kode_cpl'] }}</td>
                            <td>{{ $cpl['nama_cpl'] }}</td>
                            <td>{{ $cpl['total_nilai'] }}</td>
                            <td>{{ $cpl['total_skor_maksimal'] }}</td>
                            <td>{{ $cpl['persentase'] }}%</td>
                            <td>{{ $cpl['predikat'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tanda Tangan --}}
        <div style="margin-left: 80px; text-align: end;">
            <p>Mengetahui,</p>
            <p>Kepala Program Studi Sistem Informasi</p>
            <br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">Dr.Endina Putri Purwandari S.T.,M.KOM</p>
        </div>
    </div>
</div>
@endsection
