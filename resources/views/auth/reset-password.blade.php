@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <p class="login-box-msg">Choose a new password for your account</p>
    <x-flash-message />

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="input-group mb-3">
            <input id="email" type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email', $email) }}" required autofocus>
            <div class="input-group-append input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>

        <div class="input-group mb-3">
            <input id="password" type="password" name="password" class="form-control" placeholder="New Password" required>
            <div class="input-group-append input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>

        <div class="input-group mb-3">
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
            <div class="input-group-append input-group-text">
                <span class="fas fa-check"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </div>
        </div>
    </form>

    <p class="mt-3 mb-1">
        <a href="{{ route('login') }}">Back to login</a>
    </p>
@endsection
