<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with([
                'user',
                'details.produk'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view(
            'admin.approvals.index',
            compact('transaksis')
        );
    }

    public function show($id)
    {
        $transaksi = Transaksi::with([
                'user',
                'details.produk'
            ])
            ->findOrFail($id);

        return view(
            'admin.approvals.show',
            compact('transaksi')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve($id)
    {
        $transaksi = Transaksi::with('details.produk')
            ->findOrFail($id);

        if (
            $transaksi->approval_status !== 'pending'
        ) {

            return back()->with(
                'error',
                'Transaksi sudah diproses!'
            );
        }

        // =========================
        // KURANGI STOK
        // =========================
        foreach ($transaksi->details as $detail) {

            $produk = $detail->produk;

            if (!$produk) {
                continue;
            }

            if ($produk->stok < $detail->qty) {

                return back()->with(
                    'error',
                    "Stok {$produk->nama} tidak mencukupi!"
                );
            }

            $produk->decrement(
                'stok',
                $detail->qty
            );
        }

        // =========================
        // UPDATE STATUS
        // =========================
        $transaksi->update([

            'approval_status' => 'approved',

            'approved_at' => now(),

            'approved_by' => Auth::id(),

            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.approvals.index')
            ->with(
                'success',
                'Transaksi berhasil di-approve!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject($id)
    {
        $transaksi = Transaksi::with('details.produk')
            ->findOrFail($id);

        // =========================
        // BALIKIN STOK
        // kalau sebelumnya approved
        // =========================
        if (
            $transaksi->approval_status == 'approved'
        ) {

            foreach (
                $transaksi->details as $detail
            ) {

                $produk = $detail->produk;

                if (!$produk) {
                    continue;
                }

                $produk->increment(
                    'stok',
                    $detail->qty
                );
            }
        }

        // =========================
        // UPDATE STATUS
        // =========================
        $transaksi->update([

            'approval_status' => 'rejected',

            'approved_at' => now(),

            'approved_by' => Auth::id(),

            'status' => 'rejected',
        ]);

        return redirect()
            ->route('admin.approvals.index')
            ->with(
                'success',
                'Transaksi ditolak!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE
    |--------------------------------------------------------------------------
    */

    public function complete($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->update([

            'approval_status' => 'completed',

            'status' => 'completed',
        ]);

        return redirect()
            ->route('admin.approvals.index')
            ->with(
                'success',
                'Pesanan selesai!'
            );
    }
}