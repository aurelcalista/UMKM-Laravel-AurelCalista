<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Promo;

class TrashController extends Controller
{
    public function index()
    {
        $produks = Produk::onlyTrashed()->latest()->get();

        $kategoris = Kategori::onlyTrashed()->latest()->get();

        $promos = Promo::onlyTrashed()->latest()->get();

        return view('admin.trash.index', compact(
            'produks',
            'kategoris',
            'promos'
        ));
    }
}