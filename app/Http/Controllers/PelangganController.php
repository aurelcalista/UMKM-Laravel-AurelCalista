<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PelangganController extends Controller
{
public function index(Request $request)
{
    $query = User::where('role', 'user');
    
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%");
        });
    }
    
    $users = $query->withCount('transaksis')->latest()->paginate(10);
    
    // Tambahkan ini untuk reset requests
    $resetRequests = \App\Models\PasswordReset::where('status', 'pending')
        ->latest()
        ->paginate(10);
    
    return view('admin.pelanggan.index', compact('users', 'resetRequests'));
}
    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $newPassword = $request->password ?? 'SL-' . rand(1000, 9999) . Str::random(4);
        
        $user->password = Hash::make($newPassword);
        $user->save();
        
        return back()->with('success', "Password berhasil direset! Password baru: {$newPassword}");
    }

    public function resetPasswordFast(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $newPassword = $request->password ?? 'SL-' . Str::random(8);
        
        $user->password = Hash::make($newPassword);
        $user->save();
        
        return response()->json([
            'success' => true,
            'password' => $newPassword,
            'message' => 'Password berhasil direset'
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada pelanggan yang dipilih.');
        }
        User::whereIn('id', $ids)->delete();
        return redirect()->route('admin.pelanggan.index')->with('success', count($ids) . ' pelanggan berhasil dihapus.');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}