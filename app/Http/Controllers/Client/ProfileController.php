<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario
     */
    public function show()
    {
        $user = Auth::user();
        return view('pilot.profile', compact('user'));
    }

    /**
     * Actualizar el perfil del usuario
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Verificar contraseña actual si se está cambiando la contraseña
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }
        }
        
        // Actualizar datos del usuario
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        
        // Actualizar contraseña si se proporcionó una nueva
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('pilot.profile')->with('success', 'Perfil actualizado correctamente.');
    }
}
