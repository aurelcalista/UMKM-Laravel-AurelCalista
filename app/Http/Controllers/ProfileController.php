<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());

        // Semua transaksi user
        $transaksis = Transaksi::with('details.produk')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $pendingTransaksis = $transaksis
            ->whereIn('approval_status', [
                'pending',
                'approved'
            ]);

        $riwayatTransaksis = $transaksis
            ->whereIn('approval_status', [
                'completed',
                'rejected'
            ]);
        // Total pesanan
        $totalPesanan = $transaksis->count();

        return view('profile.show', compact(
            'user',
            'transaksis',
            'pendingTransaksis',
            'riwayatTransaksis',
            'totalPesanan'
        ));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'hp'       => 'nullable|string|max:20',
            'alamat'   => 'nullable|string|max:255',
        ]);

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'hp'       => $request->hp,
            'alamat'   => $request->alamat,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai!');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto'        => 'nullable|image|max:2048',
            'foto_base64' => 'nullable|string',
        ]);

        $user = User::findOrFail(Auth::id());

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('fotos', 'public');
            $user->update(['foto' => $path]);

            return response()->json([
                'success' => true,
                'url'     => Storage::url($path),
            ]);
        }

        if ($request->filled('foto_base64')) {
            $base64   = $request->foto_base64;
            $image    = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
            $filename = 'fotos/' . uniqid() . '.jpg';
            Storage::disk('public')->put($filename, $image);

            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $user->update(['foto' => $filename]);

            return response()->json([
                'success' => true,
                'url'     => Storage::url($filename),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada foto yang diupload.']);
    }

    public function deletePhoto()
    {
        $user = User::findOrFail(Auth::id());

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
            $user->update(['foto' => null]);
        }

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }
}