<?php
// Autor: David Osorio

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $usuario = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'direccion' => $datos['direccion'] ?? null,
            'rol' => 'cliente',
            'password' => Hash::make($datos['password']),
        ]);

        event(new Registered($usuario));

        Auth::login($usuario);

        return redirect()->route('catalogo.index');
    }
}
