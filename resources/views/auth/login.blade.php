@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="login-form-intro">
        <h2>Selamat Datang</h2>
        <p>Masuk untuk mengelola data survei, inspeksi, dan laporan PT Surveyor Indonesia.</p>
    </div>

    <x-flash-message />

    <form method="POST" action="{{ route('login.perform') }}">
        @csrf

        <div class="input-group mb-3 login-input-group">
            <span class="input-group-text">
                <i class="fas fa-envelope"></i>
            </span>
            <input id="email" type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="input-group mb-4 login-input-group">
            <span class="input-group-text password-toggle" role="button" aria-label="Toggle password visibility">
                <i class="fas fa-eye"></i>
            </span>
            <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
            <a href="{{ route('password.request') }}" class="login-link">Lupa kata sandi?</a>
            <button type="submit" class="btn btn-primary btn-login">Masuk</button>
        </div>
    </form>

    <div class="login-note text-center">
        Belum punya akun? Hubungi admin PT Surveyor Indonesia untuk meminta akses.
    </div>
@endsection
