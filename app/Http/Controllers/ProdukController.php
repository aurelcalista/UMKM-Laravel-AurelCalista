<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Promo;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('promo');

        // filter kategori
        if ($request->filled('kat')) {
            $query->where('kategori', $request->kat);
        }

        $produks = $query->latest()->get();

        $kategoris = Produk::select('kategori')
            ->distinct()
            ->pluck('kategori');

        $kategorisObj = Kategori::latest()->get();

        // promo aktif
        $promos = Promo::where('status', true)
            ->whereDate('tanggal_selesai', '>=', now())
            ->latest()
            ->get();

        return view('admin.produk.index', compact(
            'produks',
            'kategoris',
            'kategorisObj',
            'promos'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'harga'         => 'required|integer|min:0',
            'stok'          => 'required|integer|min:0',
            'kategori'      => 'required|string',
            'deskripsi'     => 'nullable|string',

            'waktu_masak'   => 'nullable|string|max:255',
            'level_pedas'   => 'nullable|string|max:255',
            'bahan_utama'   => 'nullable|string|max:255',
            'porsi'         => 'nullable|string|max:255',

            'poto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'promo_id' => 'nullable|exists:promos,id',
        ]);

     

        $data = [
            'nama'          => $request->nama,
            'harga'         => $request->harga,
            'stok'          => $request->stok,

            'kategori'   => $request->kategori,
            
            'deskripsi'     => $request->deskripsi,

            'waktu_masak'   => $request->waktu_masak,
            'level_pedas'   => $request->level_pedas,
            'bahan_utama'   => $request->bahan_utama,
            'porsi'         => $request->porsi,
            'promo_id' => $request->promo_id,
        ];

        // upload foto
        if ($request->hasFile('poto')) {

            $path = $request->file('poto')->store('produk', 'public');

            $data['poto'] = $path;
        }

        Produk::create($data);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

  
   public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        $kategoris = Produk::select('kategori')
            ->distinct()
            ->pluck('kategori');

        $kategorisObj = Kategori::latest()->get();

        // promo aktif
        $promos = Promo::where('status', true)
            ->whereDate('tanggal_selesai', '>=', now())
            ->latest()
            ->get();

        return view('admin.produk.edit', compact(
            'produk',
            'kategoris',
            'kategorisObj',
            'promos'
        ));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama'          => 'required|string|max:255',
            'harga'         => 'required|integer|min:0',
            'stok'          => 'required|integer|min:0',
            'kategori'      => 'required|string',
            'deskripsi'     => 'nullable|string',

            'waktu_masak'   => 'nullable|string|max:255',
            'level_pedas'   => 'nullable|string|max:255',
            'bahan_utama'   => 'nullable|string|max:255',
            'porsi'         => 'nullable|string|max:255',

            'poto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'promo_id' => 'nullable|exists:promos,id',
        ]);



        $data = [
            'nama'          => $request->nama,
            'harga'         => $request->harga,
            'stok'          => $request->stok,

            'kategori'      => $request->kategori,

            'deskripsi'     => $request->deskripsi,

            'waktu_masak'   => $request->waktu_masak,
            'level_pedas'   => $request->level_pedas,
            'bahan_utama'   => $request->bahan_utama,
            'porsi'         => $request->porsi,
            'promo_id' => $request->promo_id,
        ];

        // upload foto baru
        if ($request->hasFile('poto')) {

            if ($produk->poto && Storage::disk('public')->exists($produk->poto)) {
                Storage::disk('public')->delete($produk->poto);
            }

            $path = $request->file('poto')->store('produk', 'public');

            $data['poto'] = $path;
        }

        $produk->update($data);

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        $produk->delete();

        return redirect()
            ->route('admin.produk.index')
            ->with('success', 'Produk masuk ke trash!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()
                ->back()
                ->with('error', 'Tidak ada produk yang dipilih.');
        }

        $produks = Produk::whereIn('id', $ids)->get();

        foreach ($produks as $p) {

            if ($p->poto && Storage::disk('public')->exists($p->poto)) {
                Storage::disk('public')->delete($p->poto);
            }
        }

        Produk::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.produk.index')
            ->with('success', count($ids) . ' produk berhasil dihapus.');
    }
    public function trash()
    {
        $produks = Produk::onlyTrashed()->latest()->get();

        return view('admin.trash.produk', compact('produks'));
    }

    public function restore($id)
    {
        Produk::onlyTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Produk berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        $produk = Produk::onlyTrashed()->findOrFail($id);

        if ($produk->poto && Storage::disk('public')->exists($produk->poto)) {
            Storage::disk('public')->delete($produk->poto);
        }

        $produk->forceDelete();

        return back()->with('success', 'Produk dihapus permanen!');
    }
}