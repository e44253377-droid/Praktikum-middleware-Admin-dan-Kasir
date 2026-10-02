<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        return view('menu.index', [
            'products' => Product::query()
                ->where('is_active', true)
                ->orderBy('category')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
