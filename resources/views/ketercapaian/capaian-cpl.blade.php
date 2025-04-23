@extends('layouts.backend')
@section('content')
<div class="container">
    <h3>Capaian CPL Mahasiswa: {{ $mahasiswa->nama }}</h3>
    <a href="{{ route('ketercapaian.index') }}" class="btn btn-secondary mb-3">← Kembali</a>

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
@endsection
