<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'username'              => ['required', 'string', 'max:50', 'unique:users,username', 'regex:/^\S+$/'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'hp'                    => ['nullable', 'string', 'max:20'],
            'alamat'                => ['nullable', 'string', 'max:500'],
            'password'              => ['required', 'min:8', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'username.required'     => 'Username wajib diisi.',
            'username.unique'       => 'Username sudah dipakai, coba yang lain.',
            'username.regex'        => 'Username tidak boleh mengandung spasi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'hp'        => $request->hp,
            'alamat'    => $request->alamat,
            'password'  => Hash::make($request->password),
            'role'      => 'user',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home')->with('status', 'Akun berhasil dibuat! Selamat datang, ' . $user->name . ' 🎉');
    }
}