<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritController extends Controller
{
    public function toggle(Request $request)
    {
        // Favorit disimpan di localStorage (frontend)
        // Controller ini placeholder agar route tidak error
        return response()->json(['success' => true]);
    }
}