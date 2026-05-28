<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // =========================
    // LIST TRANSAKSI ADMIN
    // =========================
    public function index()
    {
        $transaksis = Transaksi::with('user')
            ->latest()
            ->paginate(10);

        return view(
            'admin.transaksi.index',
            compact('transaksis')
        );
    }

    // =========================
    // DETAIL TRANSAKSI ADMIN
    // =========================
    public function show($id)
    {
        $transaksi = Transaksi::with([
            'user',
            'details.produk'
        ])->findOrFail($id);

        return view(
            'admin.transaksi.show',
            compact('transaksi')
        );
    }

    // =========================
    // HAPUS TRANSAKSI
    // =========================
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->details()->delete();

        $transaksi->delete();

        return redirect()
            ->route('admin.transaksi.index')
            ->with(
                'success',
                'Transaksi berhasil dihapus!'
            );
    }

    // =========================
    // USER INVOICE
    // =========================
    public function invoice($id)
    {
        $transaksi = Transaksi::with([
            'user',
            'details.produk'
        ])->findOrFail($id);

        return view(
            'invoice.index',
            compact('transaksi')
        );
    }

    // =========================
    // ADMIN INVOICE
    // =========================
    public function adminInvoice($id)
    {
        $transaksi = Transaksi::with([
            'user',
            'details.produk'
        ])->findOrFail($id);

        return view(
            'admin.transaksi.invoice',
            compact('transaksi')
        );
    }

    // =========================
    // PRINT PDF
    // =========================
    public function printInvoice($id)
    {
        $transaksi = Transaksi::with([
            'user',
            'details.produk'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'invoice.index',
            compact('transaksi')
        );

        return $pdf->download(
            'invoice-' . $transaksi->id . '.pdf'
        );
    }
}