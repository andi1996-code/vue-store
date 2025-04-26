<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        try {

            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'items' => 'required|array',
                'payment_method' => 'required|string|max:255',
                'items.*.id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            DB::beginTransaction();

            try {

                // Ambil semua produk berdasarkan ID
                $productIds = collect($validated['items'])->pluck('id');
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

                // Validasi semua produk dari 1 store
                $storeIds = $products->pluck('store_id')->unique();
                if ($storeIds->count() > 1) {
                    throw new \Exception('Cart hanya boleh berisi produk dari satu toko.');
                }

                $storeId = $storeIds->first();

                // Hitung total harga (ambil harga dari database, bukan dari frontend)
                $totalPrice = 0;
                foreach ($validated['items'] as $item) {
                    $product = $products[$item['id']];
                    $totalPrice += $product->price * $item['quantity'];
                }

                // Buat order
                $order = Order::create([
                    'customer_id' => $validated['customer_id'],
                    'store_id' => $storeId,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                ]);



                // Simpan order detail dan kurangi stok
                foreach ($validated['items'] as $item) {
                    $product = $products[$item['id']];

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok untuk produk {$product->name} tidak mencukupi.");
                    }

                    $product->decrement('stock', $item['quantity']);

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'qty' => $item['quantity'],
                        'price' => $product->price,
                    ]);
                }

                DB::commit();

                return response()->json(['message' => 'Checkout berhasil!', 'order_id' => $order->id], 201);
            } catch (\Exception $e) {

                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {

            return response()->json(['message' => 'Checkout gagal!', 'error' => $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        $order = Order::with('customer')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json([
            'id' => $order->id,
            'customer' => [
                'name' => $order->customer->name,
                'phone' => $order->customer->phone,
            ],
            'address' => $order->address,
            'payment_method' => $order->payment_method,
            'status' => $order->status,
        ]);
    }
}
