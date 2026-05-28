<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\Cart;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');

        $search = $request->query('search');

        $query = Produk::with('promo');

        // filter kategori
        if ($kategori) {

            $query->where('kategori', $kategori);
        }

        // search produk
        if ($search) {

            $query->where('nama', 'like', '%' . $search . '%');
        }

        $produks = $query->latest()
            ->take(8)
            ->get();

        $kategoris = Produk::select('kategori')
            ->distinct()
            ->pluck('kategori');

        $featured = Produk::with('promo')
            ->latest()
            ->take(4)
            ->get();

        // promo aktif homepage
        $promoAktif = Promo::whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->latest()
            ->first();
        $carts = collect();

        if (Auth::check()) {

            $carts = Cart::with('produk')
                ->where('user_id', Auth::id())
                ->get();
        }

        // pending order notif
        $pendingOrdersCount = 0;

        if (Auth::check()) {

            $pendingOrdersCount = Transaksi::where('user_id', Auth::id())
                ->where('approval_status', 'pending')
                ->count();
        }
        $reviews = \App\Models\Review::latest()->take(10)->get();

        return view('home', compact(
            'produks',
            'kategoris',
            'featured',

            'promoAktif',

            'kategori',
            'search',

            'carts',

            'pendingOrdersCount',
            'reviews'
        ));
    }

    // halaman semua produk
    public function produks(Request $request)
    {
        $kategori = $request->query('kategori');

        $search = $request->query('search');

        $query = Produk::with('promo');

        if ($kategori) {

            $query->where('kategori', $kategori);
        }

        if ($search) {

            $query->where('nama', 'like', '%' . $search . '%');
        }

        $produks = $query->latest()
            ->paginate(12);

        $kategoris = Produk::select('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('produks', compact(
            'produks',
            'kategoris',
            'kategori',
            'search'
        ));
    }

    // detail produk
    public function produkDetail($id)
    {
        $produk = Produk::with('promo')
            ->findOrFail($id);

        $related = Produk::with('promo')
            ->where('kategori', $produk->kategori)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('detail_produk', compact(
            'produk',
            'related'
        ));
    }
}