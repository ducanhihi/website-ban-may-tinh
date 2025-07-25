<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{

    public function showOrderForm()
    {
        return view('admin.order-info');
    }

    public function getOrderInfo(Request $request)
    {
        $orderCode = $request->input('order_code');

        $response = Http::withHeaders([
            'Token' => env('GHN_TOKEN'),
            'Content-Type' => 'application/json'
        ])->post(env('GHN_API_URL') . '/shipping-order/detail', [
            'order_code' => $orderCode
        ]);

        if ($response->successful()) {
            return redirect()->back()->with('data', $response->json()['data']);
        }

        return redirect()->back()->with('error', $response->json()['message'] ?? 'Lỗi không xác định');
    }

}
