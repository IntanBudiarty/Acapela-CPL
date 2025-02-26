@extends('layouts.simple')

@section('title')
Login
@endsection

@section('content')
<div class="container" id="container" style="margin-top: 100px;">
    <!-- Dosen Login Container -->
    <div class="form-container dosen-login-container">
        <form action="{{ route('login.dosen') }}" method="POST">
            @csrf
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
            <h1>Login Dosen</h1>
            <input type="text" placeholder="Masukkan NIP" id="nip" name="nip" required />
            <input type="password" placeholder="Password" id="password" name="password" required />
            <button type="submit">Login</button>
        </form>
    </div>

    <!-- Admin Login Container -->
    <div class="form-container admin-login-container">
        <form action="{{ route('login.admin') }}" method="POST">
            @csrf
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
            <h1>Login Admin</h1>
            <input type="text" placeholder="Username" id="username" name="username" required />
            <input type="password" placeholder="Password" id="password" name="password" required />
            <button type="submit">Login</button>
        </form>
    </div>

    <!-- Overlay Container -->
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1 class="title">Hallo <br />Admin!</h1>
                <p>Please log in to manage and monitor the system more efficiently.</p>
                <button class="ghost" id="login-dosen">Login Admin <i class="lni lni-arrow-left login"></i></button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1 class="title">Start your <br>journey now!</h1>
                <p>If you are a lecturer, join us and start your experience with us.</p>
                <button class="ghost" id="login-admin">Login Dosen <i class="lni lni-arrow-right register"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection
