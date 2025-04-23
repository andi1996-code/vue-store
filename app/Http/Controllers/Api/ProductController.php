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

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['message' => 'Query parameter is required.'], 400);
        }

        $products = Product::with('images', 'category')
            ->where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('description', 'LIKE', '%' . $query . '%')
            ->get();

        // Tambahkan base URL ke gambar
        $products->each(function ($product) {
            $product->images->transform(function ($image) {
                $image->url = url('storage/' . $image->url);
                return $image;
            });
        });

        return response()->json($products);
    }

    public function filter(Request $request)
    {
        $query = Product::with('images', 'category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        $products = $query->get();

        // Tambahkan base URL pada gambar
        $products->each(function ($product) {
            $product->images->transform(function ($image) {
                $image->url = url('storage/' . $image->url);
                return $image;
            });
        });

        return response()->json($products);
    }
}
