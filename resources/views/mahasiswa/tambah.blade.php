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
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">{{ $judul }}</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">{{ $parent }}</li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $subparent }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Your Block -->
        <div class="col-md-6 " style="float:none;margin:auto;">
            <form method="POST" action="{{ route('form-tambah-mahasiswa') }}">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">{{ $judulform }}</h3>
                        <div class="block-options">
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-check"></i> Simpan
                            </button>
                            <button type="reset" class="btn btn-sm btn-outline-danger">Reset</button>
                            <button type="button" class="btn btn-sm btn-secondary"
                                    onclick="window.location.href='{{ redirect()->getUrlGenerator()->route('mhs') }}'">
                                Kembali
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        @csrf
                        @if(session('errors'))
                            <div class="alert alert-danger alert-dismissible fade show mb-3"
                                 role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                <strong>Aduh!</strong> Ada yang error nih :
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (Session::has('success'))
                            <div class="alert alert-success mb-3" role="alert">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger mb-3" role="alert">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <div class="row justify-content-center py-sm-3 py-md-5">
                            <div class="col-lg-10 col-lg-8">
                                <div class="mb-4">
                                    <label class="form-label" for="nim">NIM</label>
                                    <input type="text" class="form-control" id="nim"
                                           value="{{ old('nim') }}" name="nim"
                                           placeholder="Masukkan NIM..." required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="nama">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama"
                                           value="{{ old('nama') }}" name="nama"
                                           placeholder="Masukkan Nama Lengkap..." required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="angkatan">Angkatan</label>
                                    <select class="js-select2 form-select" name="angkatan" id="angkatan">
                                        @for ($year = (int)date('Y'); 1900 <= $year; $year--)
                                            <option value="{{ $year + 3 }}">{{ $year + 3 }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END Your Block -->
    </div>
    <!-- END Page Content -->
@endsection
