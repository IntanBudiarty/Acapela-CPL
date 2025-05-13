@extends('layouts.backend')

@section('title')
Dashboard
@endsection

@section('content')
<!-- Hero -->

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3 text-start">
                Hello! Hope you have a <span style="color: blue;">great day</span>.
            </h1>
            <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">App</li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="row items-push">
        <div class="col-sm-6 col-xl-4">
            <a class="block block-rounded block-fx-pop text-center h-80 mb-0"  @role('admin') href="{{ route('dosen') }}"@endrole>
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-primary-lighter mx-auto my-3">
                        <i class="fa fa-users text-primary"></i>
                    </div>
                    <div class="display-4 fw-bold">{{ $dosen }}</div>
                    <div class="text-muted mt-1">Dosen</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-4">
           <a class="block block-rounded block-fx-pop text-center h-80 mb-0" @role('admin') href="{{ route('mhs') }}"@endrole>
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-xinspire-lighter mx-auto my-3">
                        <i class="fa fa-user-graduate text-xinspire-dark"></i>
                    </div>
                    <div class="display-4 fw-bold">{{ $mahasiswa }}</div>
                    <div class="text-muted mt-1">Mahasiswa</div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-4">
            <a class="block block-rounded block-fx-pop text-center h-80 mb-0" @role('admin') href="{{ route('mk') }}"@endrole>
                <div class="block-content block-content-full">
                    <div class="item item-circle bg-xsmooth-lighter mx-auto my-3">
                        <i class="fa fa-book-open text-xsmooth"></i>
                    </div>
                    <div class="display-4 fw-bold">{{ $matakuliah}}</div>
                    <div class="text-muted mt-1">Mata Kuliah</div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- END Page Content -->
@endsection