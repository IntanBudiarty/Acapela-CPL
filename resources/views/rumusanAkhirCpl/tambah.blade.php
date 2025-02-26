
@extends('layouts.backend')

@section('title')
    {{ $judul }}
@endsection

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js_after')
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>Dashmix.helpersOnLoad(['jq-select2']);</script>
@endsection

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ $judul }}</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">{{ $parent }}</li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambahData" tabindex="-1" role="dialog" aria-labelledby="modalTambahDataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahDataLabel">Tambah Data Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <a href="{{ url('tambah-bcpl') }}" class="btn btn-sm btn-primary">Tambah</a>
                @csrf
                <div class="modal-body">
                    <!-- Form input untuk data baru -->
                    <div class="mb-3">
                        <label for="kode_cpl" class="form-label">Kode CPL</label>
                        <input type="text" class="form-control" id="kode_cpl" name="kode_cpl" required>
                    </div>
                    <div class="mb-3">
                        <label for="kode_mk" class="form-label">Kode MK</label>
                        <input type="text" class="form-control" id="kode_mk" name="kode_mk" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_mk" class="form-label">Nama MK</label>
                        <input type="text" class="form-control" id="nama_mk" name="nama_mk" required>
                    </div>
                    <div class="mb-3">
                        <label for="kode_cpmk" class="form-label">Kode CPMK</label>
                        <input type="text" class="form-control" id="kode_cpmk" name="kode_cpmk" required>
                    </div>
                    <div class="mb-3">
                        <label for="skor_maksimal" class="form-label">Skor Maksimal</label>
                        <input type="number" class="form-control" id="skor_maksimal" name="skor_maksimal" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
