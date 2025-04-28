@extends('layouts.backend')

@section('title')
    Edit Rumusan Akhir MK
@endsection

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js_after')
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        Dashmix.helpersOnLoad(['jq-select2']);

        $(document).ready(function() {
            let selectedCPMK = @json($rumusanAkhirMk->kd_cpmk ? explode(',', $rumusanAkhirMk->kd_cpmk) : []);
            let skorMaksimal = @json($rumusanAkhirMk->skor_maksimal ? json_decode($rumusanAkhirMk->skor_maksimal, true) : []);

            // Set CPMK selected
            $('#kd_cpmk').val(selectedCPMK).trigger('change');

            function renderSkorInputs(selected) {
                let skorInputs = '';
                selected.forEach(function(cpmk) {
                    skorInputs += `
                        <div class="form-group mb-3">
                            <label for="skor_maksimal_${cpmk}">Skor Maksimal untuk CPMK ${cpmk}</label>
                            <input type="number" 
                                   id="skor_maksimal_${cpmk}" 
                                   name="skor_maksimal[${cpmk}]" 
                                   value="${skorMaksimal[cpmk] ?? ''}" 
                                   class="form-control" 
                                   required>
                        </div>`;
                });
                $('#skor_inputs').html(skorInputs);
            }

            // Render awal
            renderSkorInputs(selectedCPMK);

            // Render ulang saat select CPMK berubah
            $('#kd_cpmk').on('change', function() {
                let selected = $(this).val() || [];
                renderSkorInputs(selected);
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

                {{-- Mata Kuliah --}}
                <div class="form-group mb-3">
                    <label for="mata_kuliah_id">Pilih Mata Kuliah</label>
                    <select id="mata_kuliah_id" name="mata_kuliah_id" class="form-control select2" required>
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach ($mataKuliahs as $mk)
                            <option value="{{ $mk->id }}" {{ $rumusanAkhirMk->mata_kuliah_id == $mk->id ? 'selected' : '' }}>
                                {{ $mk->kode }} - {{ $mk->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CPMK --}}
                <div class="form-group mb-3">
                    <label for="kd_cpmk">Pilih CPMK</label>
                    <select id="kd_cpmk" name="kd_cpmk[]" class="form-control select2" multiple required>
                        @foreach ($cpmks as $cpmk)
                            <option value="{{ $cpmk->kode_cpmk }}" {{ in_array($cpmk->kode_cpmk, explode(',', $rumusanAkhirMk->kd_cpmk)) ? 'selected' : '' }}>
                                {{ $cpmk->kode_cpmk }} - {{ $cpmk->nama_cpmk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Skor Maksimal --}}
                <div id="skor_inputs"></div>

                {{-- Repeater CPL & CPMK --}}
                <div id="cpl-repeater">
                    @foreach ($repeater as $index => $item)
                        <div class="cpl-item mb-4 border p-3 rounded">
                            <div class="form-group">
                                <label for="cpl-{{ $index }}">Pilih CPL</label>
                                <select name="cpl[{{ $index }}][id]" class="form-control select2" required>
                                    <option value="">-- Pilih CPL --</option>
                                    @foreach ($cpls as $cpl)
                                        <option value="{{ $cpl->kode_cpl }}" {{ $item['cpl'] == $cpl->kode_cpl ? 'selected' : '' }}>
                                            {{ $cpl->kode_cpl }} - {{ $cpl->nama_cpl }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- CPMK di dalam CPL --}}
                            <div class="cpmk-repeater mt-3">
                                @foreach ($item['cpmks'] as $cpmkIdx => $cpmkItem)
                                    <div class="cpmk-item mb-3 border p-2 rounded">
                                        <div class="form-group">
                                            <label>Pilih CPMK</label>
                                            <select name="cpl[{{ $index }}][cpmk][{{ $cpmkIdx }}][id]" class="form-control select2" required>
                                                <option value="">-- Pilih CPMK --</option>
                                                @foreach ($cpmks as $cpmk)
                                                    <option value="{{ $cpmk->kode_cpmk }}" {{ $cpmkItem['cpmk'] == $cpmk->kode_cpmk ? 'selected' : '' }}>
                                                        {{ $cpmk->kode_cpmk }} - {{ $cpmk->nama_cpmk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Skor Maksimal</label>
                                            <input type="number" 
                                                   name="cpl[{{ $index }}][cpmk][{{ $cpmkIdx }}][skor]" 
                                                   class="form-control" 
                                                   value="{{ $cpmkItem['skor'] }}" 
                                                   required>
                                        </div>

                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-success add-cpmk">Tambah CPMK</button>
                                            <button type="button" class="btn btn-sm btn-danger remove-cpmk">Hapus CPMK</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-primary add-cpl">Tambah CPL</button>
                                <button type="button" class="btn btn-sm btn-danger remove-cpl">Hapus CPL</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tombol Submit --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('rumusanAkhirMk.index') }}" class="btn btn-secondary ms-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection