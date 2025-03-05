@extends('layouts.backend')

@section('title')
    Tambah Rumusan Akhir MK
@endsection

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js_after')
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        Dashmix.helpersOnLoad(['jq-select2']);

        // Fungsi untuk menampilkan input skor untuk setiap CPMK yang dipilih
        $(document).ready(function() {
            $('#kd_cpmk').on('change', function() {
                let selectedCPMK = $(this).val();
                let skorInputs = '';

                // Generate input skor untuk setiap CPMK yang dipilih
                selectedCPMK.forEach(function(cpmk) {
                    skorInputs += `
                        <div class="form-group">
                            <label for="skor_maksimal_${cpmk}">Skor Maksimal untuk CPMK ${cpmk}</label>
                            <input type="number" id="skor_maksimal_${cpmk}" name="skor_maksimal[${cpmk}]" class="form-control" required>
                        </div>
                    `;
                });

                // Menambahkan input skor ke form
                $('#skor_inputs').html(skorInputs);
            });
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Tambah Rumusan Akhir MK</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Tambah Rumusan Akhir MK</li>
                        <li class="breadcrumb-item active" aria-current="page">Rumusan Akhir</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Form -->
        <div class="col-md-30" style="float:none; margin:auto;">
            <form method="POST" action="{{ route('rumusanAkhirMk.store') }}">
                @csrf
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Tambah Rumusan Akhir MK</h3>
                        <div class="block-options">
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-check"></i> Simpan
                            </button>
                            <button type="reset" class="btn btn-sm btn-outline-danger">Reset</button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="window.location.href='{{ route('rumusanAkhirMk.index') }}'">Kembali</button>
                        </div>
                    </div>
                    <div class="block-content">
                        <!-- Tampilkan pesan error atau sukses -->
                        @if(session('errors'))
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

                        <!-- Form Fields -->
                        <div class="form-group">
                            <label for="mata_kuliah_id">Pilih Mata Kuliah</label>
                            <select id="mata_kuliah_id" name="mata_kuliah_id" class="form-control" required>
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach ($mataKuliah as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->kode }} -> {{$mk->nama}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="kd_cpl">Kode CPL</label>
                            <select class="js-select2 form-select" id="kd_cpl" name="kd_cpl[]" class="form-control" multiple required>
                                <option value="">Pilih Kode CPL</option>
                                @foreach ($cpls as $cpl)
                                    <option value="{{ $cpl->kode_cpl }}">{{ $cpl->kode_cpl }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="kd_cpmk">Kode CPMK</label>
                            <select class="js-select2 form-select" id="kd_cpmk" name="kd_cpmk[]" class="form-control" multiple>
                                <option value="">Pilih Kode CPMK</option>
                                @foreach ($cpmks as $cpmk)
                                    <option value="{{ $cpmk->kode_cpmk }}">{{ $cpmk->kode_cpmk }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Input skor maksimal untuk setiap CPMK yang dipilih -->
                        <div id="skor-inputs-container">
                            <!-- Skor input akan dimunculkan dinamis berdasarkan CPMK yang dipilih -->
                        </div>
                        
                        <script>
                            // Update input skor maksimal ketika CPMK dipilih
                            $('#kd_cpmk').on('change', function() {
                                var selectedCpmks = $(this).val();
                                var skorInputsContainer = $('#skor-inputs-container');
                                skorInputsContainer.empty(); // Hapus input sebelumnya

                                // Loop CPMK yang dipilih dan tambahkan input skor
                                selectedCpmks.forEach(function(cpmk) {
                                    skorInputsContainer.append(`
                                        <div class="form-group">
                                            <label for="skor_maksimal_${cpmk}">Skor Maksimal untuk CPMK ${cpmk}</label>
                                            <input type="number" id="skor_maksimal_${cpmk}" name="skor_maksimal[${cpmk}]" class="form-control" required>
                                        </div>
                                    `);
                                });
                            });
                        </script>                       
                        <div id="skor_inputs"></div>

                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
