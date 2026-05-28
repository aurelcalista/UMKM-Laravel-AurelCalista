<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;  
use App\Models\Cart;          
use App\Models\Transaksi;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       
        View::composer('partials.cart', function ($view) {
            $carts = Auth::check()
                ? Cart::with('produk')->where('user_id', Auth::id())->get()
                : collect();

            $view->with('carts', $carts);
            });

        DB::listen(function ($query) {
            // Trigger saat transaksi baru dibuat
            if (str_contains($query->sql, 'insert into `transaksis`')) {
                Notifikasi::create([
                    'title' => 'Pesanan Baru!',
                    'message' => 'Ada pesanan baru yang menunggu approval',
                    'type' => 'info',
                    'link' => route('admin.approvals.index'),
                    'is_read' => false
                ]);
            }
        });
        if (config('app.debug')) {
        DB::listen(function ($query) {
            Log::info('SQL Query: ' . $query->sql);
            Log::info('Bindings: ' . json_encode($query->bindings));
        });
    }

    }
}
