<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'nama'   => 'required|string|max:100',
        'ulasan' => 'required|string|min:10|max:300',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    \App\Models\Review::create([
        'user_id' => auth()->id(),
        'nama'    => $request->nama,
        'kota'    => $request->kota,
        'menu'    => $request->menu,
        'ulasan'  => $request->ulasan,
        'rating'  => $request->rating,
    ]);

    return back()->with('success', 'Ulasan berhasil dikirim! Terima kasih 🎉');
}
}