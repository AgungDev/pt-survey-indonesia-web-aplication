<?php

namespace App\Presentation\Http\Controllers\Auth;

use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return View::make('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated(), $request->boolean('remember'))) {
            $request->session()->regenerate();

            return Redirect::intended(route('dashboard.index'));
        }

        return Redirect::back()->withErrors([ 'email' => __('auth.failed') ])->onlyInput('email');
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return Redirect::route('login');
    }
}
