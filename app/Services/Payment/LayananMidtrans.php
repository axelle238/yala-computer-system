<?php

namespace App\Services\Payment;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

/**
 * Class LayananMidtrans
 * 
 * Menangani integrasi dengan gerbang pembayaran Midtrans.
 */
class LayananMidtrans
{
    /**
     * Kunci server Midtrans.
     */
    protected $kunciServer;

    /**
     * Status lingkungan produksi atau sandbox.
     */
    protected $isProduksi;

    /**
     * URL API Midtrans.
     */
    protected $urlApi;

    public function __construct()
    {
        // Prioritaskan Pengaturan Basis Data, Cadangan ke .env
        $this->kunciServer = Setting::get('midtrans_server_key') ?: config('services.midtrans.server_key');
        $this->isProduksi = (bool) Setting::get('midtrans_is_production', config('services.midtrans.is_production', false));
        
        $this->urlApi = $this->isProduksi 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    /**
     * Mendapatkan Token Snap Midtrans untuk transaksi pesanan.
     * 
     * @param object $pesanan
     * @return array
     * @throws \Exception
     */
    public function ambilTokenSnap($pesanan)
    {
        if (empty($this->kunciServer)) {
            throw new \Exception('Kunci Server Midtrans belum dikonfigurasi di Pengaturan atau .env');
        }

        // Konstruksi Data Transaksi
        $parameter = [
            'transaction_details' => [
                'order_id' => $pesanan->order_number . '-' . rand(100,999), // ID Unik per percobaan
                'gross_amount' => (int) $pesanan->total_amount,
            ],
            'customer_details' => [
                'first_name' => $pesanan->guest_name ?? ($pesanan->user->name ?? 'Pelanggan'),
                'email' => $pesanan->user->email ?? 'tamu@yalacomputer.id',
                'phone' => $pesanan->guest_whatsapp ?? ($pesanan->user->phone ?? '08123456789'),
            ],
        ];

        $respon = Http::withBasicAuth($this->kunciServer, '')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($this->urlApi, $parameter);

        if ($respon->successful()) {
            return $respon->json(); // Mengembalikan ['token' => '...', 'redirect_url' => '...']
        }

        throw new \Exception('Kesalahan Midtrans: ' . $respon->body());
    }
}