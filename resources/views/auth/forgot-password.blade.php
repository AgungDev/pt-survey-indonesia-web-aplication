@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <p class="login-box-msg">Enter your email to receive a reset link</p>
    <x-flash-message />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="input-group mb-3">
            <input id="email" type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
            <div class="input-group-append input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
            </div>
        </div>
    </form>

    <p class="mt-3 mb-1">
        <a href="{{ route('login') }}">Back to login</a>
    </p>
@endsection
