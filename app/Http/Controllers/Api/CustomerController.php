<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }
    public function findOrCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
        ]);

        $customer = Customer::firstOrCreate(
            ['phone' => $request->phone],
            ['name' => $request->name]
        );

        return response()->json($customer);
    }
    public function lookup(Request $request)
    {
        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer) {
            return response()->json(['message' => 'Customer tidak ditemukan'], 404);
        }

        return response()->json($customer);
    }
}
