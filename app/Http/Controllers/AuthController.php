<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Allow login with email or username
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$field => $request->email, 'password' => $request->password];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'Email/username atau password salah.'])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',      // ← ganti 'nama' jadi 'name'
            'email'    => 'required|email|unique:users,email',  // ← ganti 'user' jadi 'users'
            'username' => 'required|string|unique:users,username|max:255',  // ← ganti 'user' jadi 'users'
            'password' => 'required|string|min:6|confirmed',
            'hp'       => 'nullable|string|max:20',
            'alamat'   => 'nullable|string|max:255',
        ], [
            'name.required'       => 'Nama wajib diisi.',        // ← ganti 'nama' jadi 'name'
            'email.required'      => 'Email wajib diisi.',
            'email.unique'        => 'Email sudah digunakan.',
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah digunakan.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $request->name,        // ← ganti 'nama' jadi 'name'
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'hp'       => $request->hp,
            'alamat'   => $request->alamat,
            'role'     => 'user',                // ← ganti 'pelanggan' jadi 'user'
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }
}