<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Promo;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-nonaktifkan promo expired — cukup di sini saja
        Promo::whereDate('tanggal_selesai', '<', now())
            ->where('status', true)
            ->update(['status' => false]);

        $totalProduk     = Produk::count();
        $totalPelanggan  = User::where('role', 'user')->count();
        $totalTransaksi  = Transaksi::count();
        $totalPendapatan = Transaksi::sum('total_harga');

        $weeklyIncome = Transaksi::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->sum('total_harga');

        $monthlyIncome = Transaksi::whereMonth('created_at', now()->month)
            ->sum('total_harga');

        $totalPromo = Promo::where('status', true)->count();

        $promoEndingSoon = Promo::where('status', true)
            ->whereDate('tanggal_selesai', '<=', now()->addDay())
            ->get();

        // Hitung profit
        $totalProfit  = 0;
        $transaksis   = Transaksi::with('details.produk')->get();

        foreach ($transaksis as $trx) {
            foreach ($trx->details as $item) {
                if ($item->produk) {
                    $totalProfit += ($item->harga - $item->produk->modal) * $item->qty;
                }
            }
        }

        $transaksiTerbaru = Transaksi::with('user')->latest()->take(5)->get();

        $produkTerlaris = Produk::withCount([
            'details as terjual' => fn($q) => $q->selectRaw('SUM(qty)'),
        ])->orderByDesc('terjual')->take(5)->get();

        $pelangganTerbaru = User::where('role', 'user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalPelanggan',
            'totalTransaksi',
            'totalPendapatan',
            'weeklyIncome',
            'monthlyIncome',
            'totalProfit',
            'totalPromo',
            'promoEndingSoon',
            'transaksiTerbaru',
            'produkTerlaris',
            'pelangganTerbaru',
        ));
    }
}