<?php
// app/Http/Middleware/CheckRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole {
    public function handle(Request $request, Closure $next, $role) {
        if (!auth()->check() || auth()->user()->role->name !== $role) {
            return redirect('/home')->with('error', 'Acceso no autorizado');
        }
        return $next($request);
    }
}