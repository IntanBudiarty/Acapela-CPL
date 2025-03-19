@extends('layouts.backend')

@section('title')
    Nilai Mahasiswa
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Nilai Mahasiswa</h1>
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-fx-shadow">
        <div class="block-header block-header-default">
            <h3 class="block-title">Pilih Angkatan</h3>
        </div>
        <div class="block-content">
            <form method="GET" action="{{ route('nilai.show', $mataKuliah->id) }}">
                <label for="angkatan">Pilih Angkatan:</label>
                <select name="angkatan" id="angkatan" class="form-control" onchange="this.form.submit()">
                    @foreach ($angkatanList as $angkatan)
                        <option value="{{ $angkatan }}" {{ $angkatan == $selectedAngkatan ? 'selected' : '' }}>
                            {{ $angkatan }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="block block-rounded block-fx-shadow mt-3">
        <div class="block-header block-header-default">
            <h3 class="block-title">Informasi Mata Kuliah</h3>
        </div>
        <div class="block-content">
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
        </div>
    </div>

    <div class="block block-rounded block-fx-shadow mt-3">
        <div class="block-header block-header-default">
            <h3 class="block-title">Nilai Mahasiswa</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('nilai.updateNilai') }}" method="POST">
                @csrf
                <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">

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
                            <th>Nilai Huruf</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mahasiswaList as $index => $mahasiswa)
                            @php $totalNilai = 0; @endphp
                            @foreach ($rumusanAkhirMkGrouped as $rumusans)
                                @foreach ($rumusans as $rumusan)
                                    @php
                                        $existingNilai = isset($nilaiMahasiswa[$mahasiswa->id][$rumusan->id]) 
                                            ? $nilaiMahasiswa[$mahasiswa->id][$rumusan->id]->nilai 
                                            : 0;
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

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('nilai.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
