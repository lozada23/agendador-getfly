<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirección tras login según el rol.
     */
    protected function authenticated(Request $request, $user)
    {
        $role = $user->role->name;

        switch ($role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'pilot':
                return redirect('/pilot/dashboard');
            case 'company':
                return redirect('/company/dashboard');
            default:
                auth()->logout(); // por seguridad
                return redirect('/login')->withErrors(['email' => 'Rol no autorizado.']);
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
