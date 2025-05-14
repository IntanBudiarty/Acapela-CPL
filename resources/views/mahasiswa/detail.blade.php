@extends('layouts.backend')

@section('content')
<div class="container">
    <h2>Detail Mahasiswa</h2>

    <!-- Info Mahasiswa -->
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
            <p><strong>Nama:</strong> {{ $mahasiswa->nama }}</p>
            <p><strong>Angkatan:</strong> {{ $mahasiswa->angkatan }}</p>
            <p><strong>Kelas:</strong> {{ $mahasiswa->kelas }}</p>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Dropdown Semester -->
    <form method="GET" class="mb-3">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <label for="semester" class="col-form-label">Pilih Semester:</label>
            </div>
            <div class="col-auto">
                <select name="semester" id="semester" class="form-select" onchange="this.form.submit()">
                    @for ($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}" {{ request('semester', 1) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahMatkulModal">
                    Tambah Mata Kuliah
                </button>
            </div>
        </div>
    </form>

    <!-- Daftar Mata Kuliah -->
    @php
        $selectedSemester = request('semester', 1);
        $matkuls = $mahasiswa->mataKuliahs->where('pivot.semester', $selectedSemester);
    @endphp

    <h5>Daftar Mata Kuliah - Semester {{ $selectedSemester }}</h5>

    @if ($matkuls->isEmpty())
        <p>Tidak ada mata kuliah di semester ini.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matkuls as $mk)
                    <tr>
                        <td>{{ $mk->kode }}</td>
                        <td>{{ $mk->nama }}</td>
                        <td>{{ $mk->sks }}</td>
                        <td>
                            <form action="{{ route('mahasiswa.removeMataKuliah', [$mahasiswa->id, $mk->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Modal Tambah Mata Kuliah -->
  <!-- Modal Tambah Mata Kuliah -->
<div class="modal fade" id="tambahMatkulModal" tabindex="-1" aria-labelledby="tambahMatkulModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                @if($mataKuliahs->isEmpty())
                    <p>Tidak ada mata kuliah yang tersedia.</p>
                @else
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Semester</th>
                                <th>SKS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mataKuliahs as $mataKuliah)
                                <tr>
                                    <td>{{ $mataKuliah->kode }}</td>
                                    <td>{{ $mataKuliah->nama }}</td>
                                    <td>{{ $mataKuliah->semester }}</td>
                                    <td>{{ $mataKuliah->sks }}</td>
                                    <td>
                                        <form action="{{ route('mahasiswa.addMataKuliah', ['id' => $mahasiswa->id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">
                                            <button type="submit" class="btn btn-sm btn-success">Tambah</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
