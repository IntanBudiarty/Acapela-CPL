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
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Edit Rumusan Akhir MK</h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('rumusanAkhirMk.index') }}">Rumusan Akhir MK</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-fx-shadow">
        <div class="block-header block-header-default">
            <h3 class="block-title">Edit Rumusan Akhir MK</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('rumusanAkhirMk.update', $rumusanAkhirMk->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="mata_kuliah_id">Pilih Mata Kuliah</label>
                    <select id="mata_kuliah_id" name="mata_kuliah_id" class="form-control" required>
                        @foreach ($mataKuliahs as $mk)
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
                
                        // Loop melalui CPMK yang dipilih dan tambahkan input untuk skor maksimal
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
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('rumusanAkhirMk.index') }}" class="btn btn-secondary ms-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
