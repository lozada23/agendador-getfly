<?php
// app/Http/Middleware/CheckRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = auth()->user();

        if (!$user || !$user->role || $user->role->name !== $role) {
            return redirect('/')->with('error', 'Acceso no autorizado');
        }

        return $next($request);
    }
}
