<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TampilProvisin extends Controller
{

    public function calculateShipping(Request $request)
    {
        $validated = $request->validate([
            'origin_id' => 'required|numeric',
            'destination_id' => 'required|numeric',
            'weight' => 'required|numeric|min:1',
        ]);

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'key' => 'kZUAUkyg54e55afcbe7335c4ifctNlZS',
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin_id' => $validated['origin_id'],
            'destination_id' => $validated['destination_id'],
            'weight' => $validated['weight'], // dalam gram
            'courier' => 'jne:jnt:sicepat' // bisa disesuaikan
        ]);

        if ($response->successful()) {
            return response()->json($response->json(), 200);
        }

        return response()->json([
            'error' => 'Gagal menghitung ongkir',
            'detail' => $response->json()
        ], $response->status());
    }
}
