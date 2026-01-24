<?php

namespace App\Services\Payment;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class MidtransService
{
    protected $serverKey;
    protected $isProduction;
    protected $apiUrl;

    public function __construct()
    {
        // Prioritize Database Settings, Fallback to .env
        $this->serverKey = Setting::get('midtrans_server_key') ?: config('services.midtrans.server_key');
        $this->isProduction = (bool) Setting::get('midtrans_is_production', config('services.midtrans.is_production', false));
        
        $this->apiUrl = $this->isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function getSnapToken($order)
    {
        if (empty($this->serverKey)) {
            throw new \Exception('Midtrans Server Key is not configured in Settings or .env');
        }

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
