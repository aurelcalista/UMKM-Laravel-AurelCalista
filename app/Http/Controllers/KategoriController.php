<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function store(Request $req)
    {
        Kategori::create($req->all());
        return back()->with('success', 'Kategori Ditambah');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        $kategori->delete();

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori masuk ke trash!');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $req, $id)
    {
        $k = Kategori::findOrFail($id);
        $k->update($req->all());
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori Diupdate');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada kategori yang dipilih.');
        }
        // Soft delete bulk
        Kategori::whereIn('id', $ids)->delete();
        return redirect()->route('admin.kategori.index')
            ->with('success', count($ids) . ' kategori masuk ke trash!');
    }

    // ── TRASH ─────────────────────────────────────────────
    public function trash()
    {
        $kategoris = Kategori::onlyTrashed()->latest()->get();
        return view('admin.trash.kategori', compact('kategoris'));
    }

    public function restore($id)
    {
        Kategori::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Kategori berhasil dipulihkan!');
    }

    public function forceDelete($id)
    {
        Kategori::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Kategori dihapus permanen!');
    }
}