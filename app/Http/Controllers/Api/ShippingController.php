<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    private $apiKey = 'kZUAUkyg54e55afcbe7335c4ifctNlZS';

    public function getCities(Request $request)
    {
        $search = $request->query('search', '');
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
            'search' => $search,
            'limit' => 100,
            'offset' => 0
        ]);

        if ($response->successful()) {
            return response()->json($response->json()['data']);
        }

        return response()->json(['error' => 'Failed to get destination'], 500);
    }

    public function calculateShippingCost(Request $request)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin_id' => $request->origin_id,
            'destination_id' => $request->destination_id,
            'weight' => $request->weight,
            'courier' => 'jne'
        ]);

        if ($response->successful()) {
            return response()->json($response->json()['data']);
        }

        return response()->json(['error' => 'Failed to calculate shipping cost'], 500);
    }
}
