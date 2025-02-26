@extends('layouts.backend')

@section('title')
    {{ $judul }}
@endsection

@section('css_before')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2-container {
            display: block;
        }
    </style>
@endsection

@section('js_after')
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script src="//cdn.datatables.net/plug-ins/1.11.3/sorting/natural.js"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
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
        <!-- Cari Data -->
        <div class="row">
            <div class="col-md-6 " style="float:none;margin:auto;">
                <form method="GET" action="{{URL::to('dpna/cari')}}">
                    <div class="block block-rounded block-fx-shadow">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">{{ $judulform }}</h3>
                            <div class="block-options">
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-fw fa-search"></i> Cari
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
                                        <label class="form-label" for="tahunajaran">Tahun Ajaran</label>
                                        <select class="js-select2 form-select" name="tahunajaran" id="tahunajaran">
                                            @foreach($ta as $t)
                                                <option
                                                    value="{{ Crypt::encrypt($t->id) }}"
                                                    @if (Crypt::decrypt(Request::get('tahunajaran')) === $t->id)
                                                    selected="selected"
                                                    @endif>{{$t->tahun}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="semester">Semester</label>
                                        <select class="js-select2 form-select" name="semester" id="semester">
                                            <option value="{{ Crypt::encrypt('1') }}"
                                                    @if (Crypt::decrypt(Request::get('semester')) === '1')
                                                    selected="selected"
                                                @endif>Ganjil
                                            </option>
                                            <option value="{{ Crypt::encrypt('2') }}"
                                                    @if (Crypt::decrypt(Request::get('semester')) === '2')
                                                    selected="selected"
                                                @endif>Genap
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="mk">Mata Kuliah</label>
                                        <select class="js-select2 form-select" name="mk" id="mk">
                                            @foreach($mk as $m)
                                                <option
                                                    value="{{ Crypt::encrypt($m->id) }}"
                                                    @if (Crypt::decrypt(Request::get('mk')) === $m->id)
                                                    selected="selected"
                                                    @endif>{{$m->nama}} ({{$m->kelas}})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="mhs">Mahasiswa</label>
                                        <select class="js-select2 form-select" name="mhs" id="mhs">
                                            <option value="{{ Crypt::encrypt('semua') }}">Semua</option>
                                            @foreach($mhs as $item)
                                                <option
                                                    value="{{ Crypt::encrypt($item->id) }}"
                                                    @if (Crypt::decrypt(Request::get('mhs')) === $item->id)
                                                    selected="selected"
                                                    @endif>{{$item->nim}} ({{$item->nama}})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Cari Data -->

        <!-- Dynamic Table with Export Buttons -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{$parent}} <small>List</small></h3>
                <div class="block-options">
                    <form method="GET" action="{{route('dpna-cetak')}}">
                        @csrf
                        <input name="tahun_ajaran" type="hidden"
                               value="{{ Request::get('tahunajaran') }}">
                        <input name="semester" type="hidden"
                               value="{{ Request::get('semester') }}">
                        <input name="mk" type="hidden"
                               value="{{ Request::get('mk') }}">
                        <input name="mhs" type="hidden"
                               value="{{ Request::get('mhs') }}">
                        <input type="submit" class="btn btn-sm btn-primary" value="Cetak">
                    </form>
                </div>
            </div>
            <div class="block-content block-content-full">
                @php
                    function hitung($getnilai,$bobot,$list)
                    {
                        $mhs = $getnilai->where('mahasiswa_id', $list);
                        $nilai = array();
                        foreach($mhs as $lii)
                            {
                                $nilai[] = $lii->nilai * $lii->btp->bobot;
                            }
                        return round((array_sum($nilai)/$bobot), 2);
                    }
                    function mutunilai($nilaiakhir)
                    {
                        if ($nilaiakhir >= 80 && $nilaiakhir <= 100)
                            {
                                $angka = 'A';
                                return $angka;
                            }
                        if ($nilaiakhir >= 75 && $nilaiakhir < 80)
                            {
                                $angka = 'B+';
                                return $angka;
                            }
                        if ($nilaiakhir >= 70 && $nilaiakhir < 75)
                            {
                                $angka = 'B';
                                return $angka;
                            }
                        if ($nilaiakhir >= 65 && $nilaiakhir < 70)
                            {
                                $angka = 'C+';
                                return $angka;
                            }
                        if ($nilaiakhir >= 60 && $nilaiakhir < 65)
                            {
                                $angka = 'C';
                                return $angka;
                            }
                        if ($nilaiakhir >= 40 && $nilaiakhir < 60)
                            {
                                $angka = 'D';
                                return $angka;
                            }
                        if ($nilaiakhir < 40)
                            {
                                $angka = 'E';
                                return $angka;
                            }
                        }
                    function kelulusan($nilaiakhir)
                    {
                        if ($nilaiakhir >= 60 && $nilaiakhir <= 100)
                            {
                                $angka = 'L';
                                return $angka;
                            }
                        if ($nilaiakhir < 60)
                            {
                                $angka = 'TL';
                                return $angka;
                            }
                    }
                @endphp
                @if (Crypt::decrypt(Request::get('mhs')) === 'semua')
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th style="width: 80px;">NIM</th>
                                <th style="width: 80px;">Nama Mahasiswa</th>
                                <th class="text-center" style="width: 80px;">Tugas</th>
                                <th class="text-center" style="width: 80px;">UTS</th>
                                <th class="text-center" style="width: 80px;">UAS</th>
                                <th class="text-center" style="width: 80px;">Nilai</th>
                                <th class="text-center" style="width: 80px;">Mutu</th>
                                <th class="text-center" style="width: 80px;">T/TL</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($getmhs as $li)
                                @php
                                    $hitungtugas = hitung($getnilaitugas, $getbobottugas, $li->mahasiswa->id);
                                    $hitunguts = hitung($getnilaiuts, $getbobotuts, $li->mahasiswa->id);
                                    $hitunguas = hitung($getnilaiuas, $getbobotuas, $li->mahasiswa->id);
                                    $hitungnilaiakhir = round(((hitung($getnilaitugas, $getbobottugas, $li->mahasiswa->id)*($getbobottugas/100)) + (hitung($getnilaiuts, $getbobotuts, $li->mahasiswa->id)*($getbobotuts/100)) + (hitung($getnilaiuas, $getbobotuas, $li->mahasiswa->id)*($getbobotuas/100))),2);
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $li->mahasiswa->nim }}</td>
                                    <td>{{ $li->mahasiswa->nama }}</td>
                                    <td class="text-center">{{ $hitungtugas }}</td>
                                    <td class="text-center">{{ $hitunguts }}</td>
                                    <td class="text-center">{{ $hitunguas }}</td>
                                    <td class="text-center">{{ $hitungnilaiakhir }}</td>
                                    <td class="text-center">{{ mutunilai($hitungnilaiakhir) }}</td>
                                    <td class="text-center">{{ kelulusan($hitungnilaiakhir) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th style="width: 80px;">NIM</th>
                                <th style="width: 80px;">Nama Mahasiswa</th>
                                <th class="text-center" style="width: 80px;">Tugas</th>
                                <th class="text-center" style="width: 80px;">UTS</th>
                                <th class="text-center" style="width: 80px;">UAS</th>
                                <th class="text-center" style="width: 80px;">Nilai</th>
                                <th class="text-center" style="width: 80px;">Mutu</th>
                                <th class="text-center" style="width: 80px;">T/TL</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($getmhs as $li)
                                @php
                                    $hitungtugas = hitung($getnilaitugas, $getbobottugas, $li->mahasiswa->id);
                                    $hitunguts = hitung($getnilaiuts, $getbobotuts, $li->mahasiswa->id);
                                    $hitunguas = hitung($getnilaiuas, $getbobotuas, $li->mahasiswa->id);
                                    $hitungnilaiakhir = round(((hitung($getnilaitugas, $getbobottugas, $li->mahasiswa->id)*($getbobottugas/100)) + (hitung($getnilaiuts, $getbobotuts, $li->mahasiswa->id)*($getbobotuts/100)) + (hitung($getnilaiuas, $getbobotuas, $li->mahasiswa->id)*($getbobotuas/100))),2);
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $li->mahasiswa->nim }}</td>
                                    <td>{{ $li->mahasiswa->nama }}</td>
                                    <td class="text-center">{{ $hitungtugas }}</td>
                                    <td class="text-center">{{ $hitunguts }}</td>
                                    <td class="text-center">{{ $hitunguas }}</td>
                                    <td class="text-center">{{ $hitungnilaiakhir }}</td>
                                    <td class="text-center">{{ mutunilai($hitungnilaiakhir) }}</td>
                                    <td class="text-center">{{ kelulusan($hitungnilaiakhir) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
            @endif
            <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/tables_datatables.js -->

            </div>
        </div>
        <!-- END Dynamic Table with Export Buttons -->
    </div>
    <!-- END Page Content -->
@endsection
