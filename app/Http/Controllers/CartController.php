<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Produk;
use App\Models\Transaksi;      
use App\Models\DetailTransaksi; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);

        $total = collect($cart)->sum(
            fn($i) => $i['harga'] * $i['jumlah']
        );

        $promo = Promo::where('status', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->orderByDesc('diskon')
            ->first();

        $diskon     = 0;
        $promoLabel = null;
        $totalAfter = $total;

        if ($promo && $total > 0) {

            $diskon = round(
                $total * $promo->diskon / 100
            );

            $totalAfter = $total - $diskon;

            $promoLabel =
                $promo->nama_promo .
                ' — ' .
                $promo->diskon .
                '% off';
        }

        return view('cart.index', compact(
            'cart',
            'total',
            'promo',
            'diskon',
            'promoLabel',
            'totalAfter'
        ));
    }


    public function add(Request $request)
    {
        try {

            $request->validate([
                'produk_id' => 'required',
                'jumlah'    => 'required|integer|min:1',
            ]);

            $id  = $request->produk_id;

            $qty = (int) $request->jumlah;

            $cart = Session::get('cart', []);

            $produk = Produk::findOrFail($id);

            if ($qty > $produk->stok) {

                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi',
                ], 422);
            }


            // =========================
            // UPDATE QTY
            // =========================
            if (isset($cart[$id])) {

                $newQty =
                    $cart[$id]['jumlah'] + $qty;

                if ($newQty > $produk->stok) {

                    $newQty = $produk->stok;
                }

                $cart[$id]['jumlah'] = $newQty;

            } else {

                // =========================
                // TAMBAH PRODUK KE CART
                // =========================
                $cart[$id] = [

                    'produk_id' => $produk->id,

                    'nama' => $produk->nama,

                    'harga' =>
                        (int) $produk->harga_final,

                    'jumlah' => $qty,

                    'stok' =>
                        (int) $produk->stok,

                    'poto' =>
                        $produk->poto ?? null,

                    'kategori' =>
                        $produk->kategori ?? 'menu',
                ];
            }


            Session::put('cart', $cart);

            $cartCount =
                collect($cart)->sum('jumlah');

            return response()->json([

                'success' => true,

                'message' =>
                    $produk->nama .
                    ' ditambahkan ke keranjang!',

                'cartCount' => $cartCount,

                'cart' => $cart
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }


    public function update(Request $request)
    {
        $cart = Session::get('cart', []);

        $id  = $request->input('produk_id');

        $qty = (int) $request->input(
            'jumlah',
            1
        );

        if (isset($cart[$id])) {

            $cart[$id]['jumlah'] =
                max(1, $qty);

            Session::put('cart', $cart);
        }

        if (
            $request->expectsJson() ||
            $request->ajax()
        ) {

            return response()->json([
                'success' => true
            ]);
        }

        return back();
    }


    public function remove($id)
    {
        $cart = Session::get('cart', []);

        unset($cart[$id]);

        Session::put('cart', $cart);

        return back();
    }


public function checkoutProcess(Request $request)
{

    $request->validate([
        'nama'          => 'required|string|max:255',
        'telepon'       => 'required|string|max:20',
        'alamat'        => 'nullable|string|max:255',
        'metode_kirim'  => 'required|string',
        'metode_bayar'  => 'required|string',
        'catatan'       => 'nullable|string|max:500',
        'bukti_bayar'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // <-- TAMBAHKAN INI
    ]);

    $cart = Session::get('cart', []);
    
    if (empty($cart)) {
        return redirect()->back()->with('error', 'Keranjang kosong!');
    }

    // Hitung total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['harga'] * $item['jumlah'];
    }

    $buktiPath = null;
    if ($request->hasFile('bukti_bayar')) {
        $buktiPath = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        
        // DEBUG: Cek apakah file tersimpan
        Log::info('Bukti bayar disimpan di: ' . $buktiPath);
    }

    try {
  
        $transaksi = new Transaksi();
        $transaksi->user_id = auth()->id();
        $transaksi->total_harga = $total;
        $transaksi->tanggal = date('Y-m-d');
        $transaksi->status = 'pending';
        $transaksi->approval_status = 'pending';
        $transaksi->metode_bayar = $request->metode_bayar;
        $transaksi->metode_kirim = $request->metode_kirim;
        $transaksi->alamat = $request->alamat ?? '';
        $transaksi->catatan = $request->catatan ?? '';
        $transaksi->bukti_bayar = $buktiPath; 
        $transaksi->save();

        // Insert detail
        foreach ($cart as $id => $item) {
            $detail = new DetailTransaksi();
            $detail->transaksi_id = $transaksi->id;
            $detail->produk_id = $id;
            $detail->qty = $item['jumlah'];
            $detail->harga = $item['harga'];
            $detail->foto = $item['poto'] ?? null;
            $detail->save();
        }

        Session::forget('cart');

        return redirect()->route('profile.show')->with([
            'success' => 'Pesanan berhasil!',
            'open_panel' => 'pesanan'
        ]);

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}
}