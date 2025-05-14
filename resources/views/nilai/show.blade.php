@extends('layouts.backend')

@section('title')
    Nilai Mahasiswa
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="content">
            <div class="container">
                <div class="bg-body-light">
                    <div class="content">
                        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Nilai Mahasiswa</h1>
                    </div>
                </div>

                <div class="block block-rounded block-fx-shadow">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Pilih Angkatan</h3>
                    </div>
                    <div class="block-content">
                        <form method="GET" action="{{ route('nilai.show', $mataKuliah->id) }}">
                            <label for="angkatan">Pilih Angkatan:</label>
                            <select name="angkatan" id="angkatan" class="form-control mb-4" onchange="this.form.submit()">
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
                            <a href="{{ route('nilai.index') }}" class="btn btn-secondary mb-4"><i class="fa fa-turn-left"></i> Kembali</a>
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
                                        @foreach ($rumusanAkhirMkGrouped as $rumusans)
                                            @foreach ($rumusans as $rumusan)
                                                <tr>
                                                    @if ($loop->first && $loop->parent->first)
                                                        <td rowspan="{{ $rumusanAkhirMkGrouped->flatten()->count() }}">{{ $index + 1 }}</td>
                                                        <td rowspan="{{ $rumusanAkhirMkGrouped->flatten()->count() }}">{{ $mahasiswa->nim }}</td>
                                                        <td rowspan="{{ $rumusanAkhirMkGrouped->flatten()->count() }}">{{ $mahasiswa->nama }}</td>
                                                    @endif

                                                    <td>{{ \App\Models\Cpl::find($rumusan->kd_cpl)->kode_cpl }}</td>
                                                    <td>{{ \App\Models\Cpmk::find($rumusan->kd_cpmk)->kode_cpmk }}</td>
                                                    <td>{{ $rumusan->skor_maksimal }}</td>
                                                    <td>
                                                        <input 
                                                            type="number" 
                                                            class="form-control nilai-input" 
                                                            name="nilai[{{ $mahasiswa->id }}][{{ $rumusan->id }}]" 
                                                            value="{{ old('nilai.' . $mahasiswa->id . '.' . $rumusan->id, $nilaiMahasiswa[$mahasiswa->id][$rumusan->id]->nilai ?? 0) }}" 
                                                            max="{{ $rumusan->skor_maksimal }}" 
                                                            min="0"
                                                            data-mahasiswa="{{ $mahasiswa->id }}"
                                                            required
                                                        >
                                                    </td>

                                                    @if ($loop->first && $loop->parent->first)
                                                        <td rowspan="{{ $rumusanAkhirMkGrouped->flatten()->count() }}" id="total-{{ $mahasiswa->id }}" class="text-center align-middle">
                                                            0
                                                        </td>
                                                        <td rowspan="{{ $rumusanAkhirMkGrouped->flatten()->count() }}" id="akumulasi-{{ $mahasiswa->nim }}" class="text-center align-middle">
                                                            <strong>{{ $mahasiswa->grade ?? 'N/A' }}</strong>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-outline-primary mb-4"><i class="fa fa-check"></i> Simpan</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function hitungTotalNilai(mahasiswaId) {
        let total = 0;
        document.querySelectorAll(`input[data-mahasiswa='${mahasiswaId}']`).forEach(input => {
            const nilai = parseFloat(input.value) || 0;
            total += nilai;
        });
        document.getElementById(`total-${mahasiswaId}`).textContent = total;
    }

    // Update total setiap kali input nilai berubah
    document.querySelectorAll('.nilai-input').forEach(input => {
        input.addEventListener('input', function() {
            const max = parseFloat(this.getAttribute('max'));
            let value = parseFloat(this.value);

            if (value > max) this.value = max;
            if (value < 0) this.value = 0;

            hitungTotalNilai(this.dataset.mahasiswa);
        });

        // Hitung total saat halaman pertama kali dibuka
        hitungTotalNilai(input.dataset.mahasiswa);
    });
</script>
@endpush
@endsection
