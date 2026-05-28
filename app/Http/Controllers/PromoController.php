<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    // ── Helper: auto-nonaktifkan promo expired ─────────────
    private function autoExpire(): void
    {
        Promo::whereDate('tanggal_selesai', '<', now())
            ->where('status', true)
            ->update(['status' => false]);
    }

    // ── INDEX ──────────────────────────────────────────────
    public function index()
    {
        $this->autoExpire();

        $promos = Promo::latest()->get();

        return view('admin.promo.index', compact('promos'));
    }

    // ── STORE ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nama_promo'      => 'required|string|max:255',
            'diskon'          => 'required|integer|min:1|max:100',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'deskripsi'       => 'nullable|string',
            'banner'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'nama_promo',
            'diskon',
            'tanggal_mulai',
            'tanggal_selesai',
            'deskripsi',
        ]);

        $data['status'] = now()->between(
            $request->tanggal_mulai,
            $request->tanggal_selesai
        );

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')
                ->store('promo', 'public');
        }

        Promo::create($data);

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil ditambahkan!');
    }

    // ── UPDATE ─────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        $request->validate([
            'nama_promo'      => 'required|string|max:255',
            'diskon'          => 'required|integer|min:1|max:100',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'deskripsi'       => 'nullable|string',
            'banner'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'nama_promo',
            'diskon',
            'tanggal_mulai',
            'tanggal_selesai',
            'deskripsi',
        ]);

        // Auto-update status berdasarkan tanggal
        $data['status'] = now()->between(
            $request->tanggal_mulai,
            $request->tanggal_selesai
        );

        if ($request->hasFile('banner')) {
            if ($promo->banner && Storage::disk('public')->exists($promo->banner)) {
                Storage::disk('public')->delete($promo->banner);
            }
            $data['banner'] = $request->file('banner')
                ->store('promo', 'public');
        }

        $promo->update($data);

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil diperbarui!');
    }

    // ── DESTROY (soft delete) ──────────────────────────────
   public function destroy($id)
    {
        $promo = Promo::findOrFail($id);

        $promo->delete();

        return redirect()
            ->route('admin.promo.index')
            ->with('success', 'Promo masuk ke trash!');
    }

    // ── TRASH ──────────────────────────────────────────────
    public function trash()
    {
        $promos = Promo::onlyTrashed()->latest()->get();

        return view('admin.trash.promo', compact('promos'));
    }

    public function restore($id)
    {
        Promo::onlyTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Promo berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $promo = Promo::onlyTrashed()->findOrFail($id);

        if ($promo->banner && Storage::disk('public')->exists($promo->banner)) {
            Storage::disk('public')->delete($promo->banner);
        }

        $promo->forceDelete();

        return back()->with('success', 'Promo dihapus permanen!');
    }
}