<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images', 'category')->get();

        // Tambahkan base URL ke setiap gambar
        $products->each(function ($product) {
            $product->images->transform(function ($image) {
                $image->url = url('storage/' . $image->url); // Tambahkan base URL
                return $image;
            });
        });

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with('images', 'category')->findOrFail($id);

        // Tambahkan base URL ke setiap gambar
        $product->images->transform(function ($image) {
            $image->url = url('storage/' . $image->url);
            return $image;
        });

        return response()->json($product);
    }
}
