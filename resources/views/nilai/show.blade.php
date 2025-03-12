@extends('layouts.backend')  

@section('title')
    Nilai Mahasiswa
@endsection

@section('content') 
<div class="bg-body-light"> 
    <div class="content content-full d-flex justify-content-between align-items-center">
        <h1 class="fs-3 fw-semibold my-2 my-sm-3">Nilai Mahasiswa</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('nilai.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="#" class="btn btn-primary">Simpan</a>
        </div>
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-fx-shadow">
        <div class="block-header block-header-default">
            <h3 class="block-title">Informasi Mahasiswa</h3>
        </div>
        <div class="block-content">
            <!-- Informasi Mata Kuliah -->
            <table class="table table-bordered">
                <tr>
                    <td><strong>Kode MK:</strong></td>
                    <td>{{ $mataKuliah->kode }}</td>
                </tr>
                <tr>
                    <td><strong>Nama MK:</strong></td>
                    <td>{{ $mataKuliah->nama }}</td>
                </tr>
            </table>

            <form action="{{ route('nilai.updateNilai') }}" method="POST">
                @csrf
                <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">

                <!-- Tabel Input Nilai -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>CPL</th>
                            <th>CPMK</th>
                            <th>Skor Maks</th>
                            <th>Nilai</th>
                            <th>Total</th>
                            <th>Akumulasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mahasiswaList as $index => $mahasiswa)
                            @php 
                                $totalNilai = 0; 
                            @endphp
                            @foreach ($rumusanAkhirMkGrouped as $rumusans)
                                @foreach ($rumusans as $rumusan)
                                    @php
                                        // Ambil nilai yang sudah tersimpan untuk mahasiswa dan rumusan ini
                                        $existingNilai = isset($nilaiMahasiswa[$mahasiswa->id][$rumusan->id]) 
                                            ? $nilaiMahasiswa[$mahasiswa->id][$rumusan->id]->nilai 
                                            : 0;
                                        // Menjumlahkan nilai yang ada
                                        $totalNilai += $existingNilai;
                                    @endphp
                                    <tr>
                                        @if ($loop->first)
                                            <td rowspan="{{ count($rumusans) }}">{{ $index + 1 }}</td>
                                            <td rowspan="{{ count($rumusans) }}">{{ $mahasiswa->nim }}</td>
                                            <td rowspan="{{ count($rumusans) }}">{{ $mahasiswa->nama }}</td>
                                        @endif
                                        <td>{{ $rumusan->kd_cpl }}</td>
                                        <td>{{ $rumusan->kd_cpmk }}</td>
                                        <td>{{ $rumusan->skor_maksimal }}</td>
                                        <td>
                                            <input 
                                                type="number" 
                                                class="form-control nilai-input" 
                                                name="nilai[{{ $mahasiswa->id }}][{{ $rumusan->id }}]" 
                                                value="{{ old('nilai.' . $mahasiswa->id . '.' . $rumusan->id, $existingNilai) }}" 
                                                data-nim="{{ $mahasiswa->nim }}" 
                                                data-total-id="total-{{ $mahasiswa->nim }}"
                                                required>
                                        </td>
                                        @if ($loop->last)
                                            <td id="total-{{ $mahasiswa->nim }}">
                                                <strong>{{ $totalNilai }}</strong>
                                            </td>
                                            <td id="akumulasi-{{ $mahasiswa->nim }}">
                                                <strong>{{ $mahasiswa->grade ?? 'N/A' }}</strong>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>                    
                </table>
               

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nilaiInputs = document.querySelectorAll('.nilai-input');

        nilaiInputs.forEach(input => {
            input.addEventListener('input', function() {
                const nim = this.getAttribute('data-nim');
                const totalId = this.getAttribute('data-total-id');
                let totalNilai = 0;

                // Menjumlahkan semua nilai untuk NIM yang sama
                document.querySelectorAll(`.nilai-input[data-nim="${nim}"]`).forEach(el => {
                    totalNilai += parseInt(el.value) || 0;
                });

                // Menampilkan total di kolom Total
                document.getElementById(totalId).innerText = totalNilai;

                // Tentukan akumulasi (grade) berdasarkan total nilai
                const akumulasi = getGrade(totalNilai);
                document.getElementById(`akumulasi-${nim}`).innerText = akumulasi;
            });
        });

        // Fungsi untuk menentukan grade (akumulasi) berdasarkan total nilai
        function getGrade(total) {
            if (total >= 85) {
                return 'A';
            } else if (total >= 80) {
                return 'A-';
            } else if (total >= 75) {
                return 'B+';
            } else if (total >= 70) {
                return 'B';
            } else if (total >= 65) {
                return 'B-';
            } else if (total >= 60) {
                return 'C+';
            } else if (total >= 50) {
                return 'C';
            } else {
                return 'D';
            }
        }
    });
</script>

@endsection
