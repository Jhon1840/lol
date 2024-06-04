<?php

namespace App\Http\Controllers;

use App\Tables\Products;
use Illuminate\Http\Request;

class TablarKitController extends Controller
{
    public function products(ProductController $table, Request $request)
    {
        if ($request->expectsJson()) {
            return $table->getData($request);
        }

        return view('products.index', compact('table'));
    }
}