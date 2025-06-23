<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function redirectTo()
    {
        // Redirigir según el rol del usuario
        if (auth()->user()->role_id == 1) {
            return '/admin'; // Admin
        }
        return '/client'; // Cliente
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}