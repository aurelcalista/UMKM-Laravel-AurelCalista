<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // Tampilkan form lupa password
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    // USER: Kirim request reset password
    public function sendRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Cek apakah sudah ada request pending
        $existing = PasswordReset::where('email', $request->email)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'Permintaan sudah dikirim. Silakan tunggu admin memproses.');
        }

        // Buat request baru
        PasswordReset::create([
            'email' => $request->email,
            'token' => Str::random(64),
            'status' => 'pending',
            'approved' => false,
            'expires_at' => now()->addDays(3),
        ]);

        return back()->with('success', 'Permintaan reset password telah dikirim ke admin.');
    }

    // USER: Cek status dan lihat password sementara
    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $reset = PasswordReset::where('email', $request->email)
            ->where('status', 'approved')
            ->where('expires_at', '>', now())
            ->first();

        if (!$reset) {
            return back()->with('error', 'Belum ada permintaan yang disetujui. Silakan buat permintaan baru.');
        }

        return view('auth.show-temp-password', [
            'email' => $request->email,
            'temp_password' => $reset->temp_password,
            'expires_at' => $reset->expires_at
        ]);
    }

    // ADMIN: Lihat semua request
    public function adminIndex()
    {
        $requests = PasswordReset::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.password-resets.index', compact('requests'));
    }

    // ADMIN: Approve request dan generate password random
    public function approve($id)
    {
        $reset = PasswordReset::findOrFail($id);

        if ($reset->status !== 'pending') {
            return back()->with('error', 'Request sudah diproses.');
        }

        $user = User::where('email', $reset->email)->firstOrFail();

        // Generate password random (8 karakter)
        $tempPassword = Str::random(8);

        // Update user password
        $user->password = Hash::make($tempPassword);
        $user->save();

        // Update reset request
        $reset->update([
            'status' => 'approved',
            'approved' => true,
            'temp_password' => $tempPassword,
            'expires_at' => now()->addDays(3),
        ]);

        return back()->with('success', "Password sementara: {$tempPassword}");
    }

    // ADMIN: Reject request
    public function reject($id)
    {
        $reset = PasswordReset::findOrFail($id);
        $reset->delete();

        return back()->with('success', 'Permintaan reset password ditolak.');
    }

    // USER: Login dengan password sementara
    public function loginWithTempPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan');
        }

        // Cek apakah ini password sementara
        $reset = PasswordReset::where('email', $request->email)
            ->where('status', 'approved')
            ->where('temp_password', $request->password)
            ->where('expires_at', '>', now())
            ->first();

        if ($reset) {
            // Login dengan password sementara
            auth()->login($user);

            // Tandai sebagai used
            $reset->status = 'used';
            $reset->save();

            // Redirect ke halaman ganti password
            return redirect()->route('password.force-change')
                ->with('warning', 'Anda menggunakan password sementara. Silakan ganti password Anda sekarang.');
        }

        // Coba login biasa
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/');
        }

        return back()->with('error', 'Email atau password salah');
    }

    // Tampilkan halaman force ganti password
    public function showForceChangeForm()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        return view('auth.force-change-password');
    }

    // Proses force ganti password
    public function forceChangePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Password berhasil diubah!');
    }
}