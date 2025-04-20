<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Mengambil semua produk dengan relasi (misalnya gambar, kategori, dll.)
        $products = Product::with('images', 'category')->get();

        return response()->json($products);
    }
}
