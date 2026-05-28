<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $transaksi = Transaksi::with([
            'details.produk',
            'user'
        ])->findOrFail($id);

        // keamanan user
        if (
            auth()->user()->role !== 'admin' &&
            $transaksi->user_id != auth()->id()
        ) {
            abort(403);
        }

        return view('admin.transaksi.invoice', compact('transaksi'));
    }

    public function print($id)
    {
        $transaksi = Transaksi::with([
            'details.produk',
            'user'
        ])->findOrFail($id);

        // keamanan user
        if (
            auth()->user()->role !== 'admin' &&
            $transaksi->user_id != auth()->id()
        ) {
            abort(403);
        }

        return view('admin.transaksi.invoice', compact('transaksi'));
    }
}