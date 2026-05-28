<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        if (DB::getSchemaBuilder()->hasColumn('users', 'last_login_at')) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['last_login_at' => now()]);
        }

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard')
                ->with('status', 'Selamat datang, Admin!');
        }

        return redirect()->route('home')
            ->with('status', 'Login berhasil! Selamat datang, ' . $user->name . ' 👋');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('status', 'Kamu telah berhasil logout.');
    }
}