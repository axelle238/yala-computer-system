<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;

class MidtransService
{
    protected $serverKey;
    protected $isProduction;
    protected $apiUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        $this->isProduction = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        
        $this->apiUrl = $this->isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function getSnapToken($order)
    {
        // Payload Construction
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number . '-' . rand(100,999), // Unique ID per attempt
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->guest_name ?? $order->user->name ?? 'Guest',
                'email' => $order->user->email ?? 'guest@example.com',
                'phone' => $order->guest_whatsapp ?? '08123456789',
            ],
            // Optional: Item Details for better invoice in Midtrans
            // 'item_details' => ... 
        ];

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($this->apiUrl, $params);

        if ($response->successful()) {
            return $response->json(); // Returns ['token' => '...', 'redirect_url' => '...']
        }

        throw new \Exception('Midtrans Error: ' . $response->body());
    }
}
