<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.komerce_key');
        if (empty($this->apiKey)) {
            throw new \Exception('API key Komerce tidak dikonfigurasi');
        }
    }

    public function getCities(Request $request)
    {
        try {
            // Tetapkan nilai default untuk parameter
            $search = $request->query('search', ''); // Default ke string kosong
            $limit = $request->query('limit', 10);  // Default ke 10
            $offset = $request->query('offset', 0); // Default ke 0

            // Kirim permintaan ke API eksternal
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $search,
                'limit' => $limit,
                'offset' => $offset
            ]);

            // Logging untuk debugging
            Log::info('Komerce API Request', [
                'url' => 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination',
                'params' => [
                    'search' => $search,
                    'limit' => $limit,
                    'offset' => $offset
                ],
                'response' => $response->body()
            ]);

            // Periksa apakah respons berhasil
            if ($response->successful()) {
                $data = $response->json();

                if ($data['meta']['code'] !== 200) {
                    throw new \Exception($data['meta']['message'] ?? 'Response tidak valid');
                }

                // Format data untuk respons
                return response()->json([
                    'success' => true,
                    'data' => array_map(function ($item) {
                        return [
                            'city_id' => $item['id'],
                            'city_name' => $item['city_name'],
                            'district_name' => $item['district_name'],
                            'subdistrict_name' => $item['subdistrict_name'],
                            'postal_code' => $item['zip_code'],
                            'label' => $item['label'],
                            'province_name' => $item['province_name']
                        ];
                    }, $data['data'])
                ]);
            } else {
                // Log error jika respons gagal
                Log::error('Komerce API Error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception('Gagal mengambil data dari API Komerce');
            }
        } catch (\Exception $e) {
            // Tangani error dan log pesan
            Log::error('Error in getCities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function calculateShipping(Request $request)
    {
        try {
            $validated = $request->validate([
                'origin' => 'required|integer',
                'destination' => 'required|integer',
                'weight' => 'required|integer|min:1',
                'courier' => 'required|string|in:jne,tiki,pos',
            ]);

            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->post('https://rajaongkir.komerce.id/api/v1/cost', [
                'origin' => $validated['origin'],
                'destination' => $validated['destination'],
                'weight' => $validated['weight'],
                'courier' => $validated['courier'] ?? 'jne'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['meta']['code'] !== 200) {
                    throw new \Exception($data['meta']['message'] ?? 'Response tidak valid');
                }

                return response()->json($data['data']);
            } else {
                throw new \Exception('Gagal menghitung ongkos kirim');
            }
        } catch (\Exception $e) {
            Log::error('Error in calculateShipping: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //cek ongkir
    public function getShippingCost(Request $request)
    {
        try {
            $validated = $request->validate([
                'origin' => 'required|integer',
                'destination' => 'required|integer',
                'weight' => 'required|integer|min:1',
                'courier' => 'required|string|in:jne,tiki,pos',
            ]);

            $response = Http::withHeaders([
                'key' => $this->apiKey,
            ])->post('https://rajaongkir.komerce.id/api/v1/cost', [
                'origin' => $request->origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier,
            ]);

            if ($response->successful()) {
                Log::info('RajaOngkir API Response', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json($response->json());
            } else {
                Log::error('RajaOngkir API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json(['message' => 'Failed to fetch shipping cost'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error in getShippingCost: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProvinces(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get('https://rajaongkir.komerce.id/api/v1/province');

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                throw new \Exception('Gagal mengambil data dari API Komerce');
            }
        } catch (\Exception $e) {
            Log::error('Error in getProvinces: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchDestination(Request $request)
    {
        try {
            $search = $request->query('search', '');
            $limit = $request->query('limit', 10);
            $offset = $request->query('offset', 0);

            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $search,
                'limit' => $limit,
                'offset' => $offset
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                throw new \Exception('Gagal mengambil data dari API Komerce');
            }
        } catch (\Exception $e) {
            Log::error('Error in searchDestination: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCityById($id)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get("https://rajaongkir.komerce.id/api/v1/destination/domestic-destination/{$id}");

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                throw new \Exception('Gagal mengambil data dari API Komerce');
            }
        } catch (\Exception $e) {
            Log::error('Error in getCityById: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCityByName($name)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get("https://rajaongkir.komerce.id/api/v1/destination/domestic-destination", [
                'search' => $name,
                'limit' => 1,
                'offset' => 0
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                throw new \Exception('Gagal mengambil data dari API Komerce');
            }
        } catch (\Exception $e) {
            Log::error('Error in getCityByName: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
