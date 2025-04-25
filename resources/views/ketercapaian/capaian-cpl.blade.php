@extends('layouts.backend')
@section('content')
<div class="card">
    <div class="card-body">

        {{-- Kop Surat --}}
        <div class="row align-items-center mb-4">
            <div class="col-2 text-center">
                <img src="{{ asset('media/photos/ketercapaian/Logo-unib.png') }}" alt="Logo UNIB" width="75" height="75">
            </div>
            <div class="col-10 text-right">
                <h4 style="margin:0;">UNIVERSITAS BENGKULU</h4>
                <h6 style="margin:0;">FAKULTAS TEKNIK</h6>
                <h6 style="margin:0; font-size: 12px">PROGRAM STUDI SISTEM INFORMASI</h6>
                {{-- <hr style="border: 1px solid #000;"> --}}
            </div>
        </div>

        {{-- Data Mahasiswa --}}
        <div class="row">
            <div class="col">
                <table class="table table-borderless" style="font-size: 12px;">
                    <tr>
                        <td style="padding: 4px;"><strong>Nama Mahasiswa:</strong></td>
                        <td style="padding: 4px;">{{ $mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px;"><strong>NIM:</strong></td>
                        <td style="padding: 4px;">{{ $mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px;"><strong>Angkatan:</strong></td>
                        <td style="padding: 4px;">{{ $mahasiswa->angkatan }}</td>
                    </tr>
                </table>                
            </div>
            <div class="col">
                <table class="table table-borderless" style="font-size: 12px">
                    <tr>
                        <td style="padding: 4px"><strong>Fakultas:</strong></td>
                        <td style="padding: 4px">TEKNIK</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px"><strong>Program Studi:</strong></td>
                        <td style="padding: 4px">SISTEM INFORMASI</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Tabel CPL --}}
        <div class="mt-1">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="font-size: 11px;">Kode CPL</th>
                        <th style="font-size: 11px;">Nama CPL</th>
                        <th style="font-size: 11px;">Total Nilai</th>
                        <th style="font-size: 11px;">Total Skor Maksimal</th>
                        <th style="font-size: 11px;">Persentase (%)</th>
                        <th style="font-size: 11px;">Predikat</th>
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
            <div style="margin-left: 80px; text-align: end;">
                <p style="margin-bottom: 5px;">Mengetahui,</p>
                <p style="margin-bottom: 5px;">Kepala Program Studi Sistem Informasi</p>
                <br><br><br>
                <p style="font-weight: bold; text-decoration: underline; margin-bottom: 3px;">{{ $kaprodi?->nama ?? "Kaprodi Tidak Di Temukan" }}</p>
                <p style="margin-top: 0;">NIP. {{ $kaprodi?->nip ?? "NIP Tidak Di Temukan"}}</p>
            </div>            
        </div>
    </div>
</div>
<script>
    window.onload = function() {
        window.print();
    }
</script>
@endsection
