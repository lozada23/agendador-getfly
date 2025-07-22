<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectByRole
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $role = Auth::user()->role->name;

        switch ($role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'Pilot':
                return redirect('/pilot/dashboard');
            case 'company':
                return redirect('/company/dashboard');
            default:
                abort(403, 'Rol no reconocido');
        }
    }
}
