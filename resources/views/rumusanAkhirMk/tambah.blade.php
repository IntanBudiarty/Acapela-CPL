@extends('layouts.backend')

@section('title')
    Tambah Rumusan Akhir MK
@endsection

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js_after')
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        Dashmix.helpersOnLoad(['jq-select2']);

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: '-- Pilih --',
                width: '100%'
            });
        });

        let cplIndex = 0;

        // Tambah CPL
        $(document).on('click', '.add-cpl', function() {
            cplIndex++;
            let newCpl = `
                <div class="cpl-item mb-4">
                    <div class="form-group">
                        <label>Pilih CPL</label>
                        <select name="cpl[${cplIndex}][id]" class="form-control select2" required>
                            <option value="">-- Pilih CPL --</option>
                            @foreach ($cpls as $cpl)
                                <option value="{{ $cpl->id }}">{{ $cpl->kode_cpl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="cpmk-repeater">
                        <div class="cpmk-item">
                            <div class="form-group">
                                <label>Pilih CPMK</label>
                                <select name="cpl[${cplIndex}][cpmk][0][id]" class="form-control select2" required>
                                    <option value="">-- Pilih CPMK --</option>
                                    @foreach ($cpmks as $cpmk)
                                        <option value="{{ $cpmk->id }}">{{ $cpmk->kode_cpmk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Skor Maksimal</label>
                                <input type="number" name="cpl[${cplIndex}][cpmk][0][skor]" class="form-control" required>
                            </div>
                            <button type="button" class="btn btn-sm btn-success add-cpmk mt-2">Tambah CPMK</button>
                            <button type="button" class="btn btn-sm btn-danger remove-cpmk mt-2">Hapus CPMK</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-primary add-cpl mt-3">Tambah CPL</button>
                    <button type="button" class="btn btn-sm btn-danger remove-cpl mt-3">Hapus CPL</button>

                    <hr>
                </div>
            `;
            $('#cpl-repeater').append(newCpl);
            $('#cpl-repeater').find('.select2').select2({ width: '100%' }); // Reinitialize Select2 setelah append
        });

        // Hapus CPL
        $(document).on('click', '.remove-cpl', function() {
            $(this).closest('.cpl-item').remove();
        });

        // Tambah CPMK
        $(document).on('click', '.add-cpmk', function() {
            let cplParent = $(this).closest('.cpl-item');
            let cplIdx = $('#cpl-repeater .cpl-item').index(cplParent);
            let cpmkIdx = cplParent.find('.cpmk-item').length;

            let newCpmk = `
                <div class="cpmk-item">
                    <div class="form-group">
                        <label>Pilih CPMK</label>
                        <select name="cpl[${cplIdx}][cpmk][${cpmkIdx}][id]" class="form-control select2" required>
                            <option value="">-- Pilih CPMK --</option>
                            @foreach ($cpmks as $cpmk)
                                <option value="{{ $cpmk->id }}">{{ $cpmk->kode_cpmk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Skor Maksimal</label>
                        <input type="number" name="cpl[${cplIdx}][cpmk][${cpmkIdx}][skor]" class="form-control" required>
                    </div>
                    <button type="button" class="btn btn-sm btn-success add-cpmk mt-2">Tambah CPMK</button>
                    <button type="button" class="btn btn-sm btn-danger remove-cpmk mt-2">Hapus CPMK</button>
                </div>
            `;

            cplParent.find('.cpmk-repeater').append(newCpmk);
            cplParent.find('.select2').select2({ width: '100%' }); // Reinitialize Select2 setelah append
        });

        // Hapus CPMK
        $(document).on('click', '.remove-cpmk', function() {
            $(this).closest('.cpmk-item').remove();
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

                    <div class="form-group mb-3">
                        <label for="mata_kuliah_id">Pilih Mata Kuliah</label>
                        <select id="mata_kuliah_id" name="mata_kuliah_id" class="form-control select2" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach ($mataKuliah as $mk)
                                <option value="{{ $mk->id }}">{{ $mk->kode }} -> {{$mk->nama}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="cpl-repeater">
                        <div class="cpl-item mb-4">
                            <div class="form-group">
                                <label>Pilih CPL</label>
                                <select name="cpl[0][id]" class="form-control select2" required>
                                    <option value="">-- Pilih CPL --</option>
                                    @foreach ($cpls as $cpl)
                                        <option value="{{ $cpl->id }}">{{ $cpl->kode_cpl }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="cpmk-repeater">
                                <div class="cpmk-item">
                                    <div class="form-group">
                                        <label>Pilih CPMK</label>
                                        <select name="cpl[0][cpmk][0][id]" class="form-control select2" required>
                                            <option value="">-- Pilih CPMK --</option>
                                            @foreach ($cpmks as $cpmk)
                                                <option value="{{ $cpmk->id }}">{{ $cpmk->kode_cpmk }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Skor Maksimal</label>
                                        <input type="number" name="cpl[0][cpmk][0][skor]" class="form-control" required>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-success add-cpmk mt-2">Tambah CPMK</button>
                                    <button type="button" class="btn btn-sm btn-danger remove-cpmk mt-2">Hapus CPMK</button>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-primary add-cpl mt-3">Tambah CPL</button>
                            <button type="button" class="btn btn-sm btn-danger remove-cpl mt-3">Hapus CPL</button>

                            <hr>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
<!-- END Page Content -->

@endsection
