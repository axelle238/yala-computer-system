<?php

namespace App\Livewire\Store;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher; // New Import
use App\Services\Payment\MidtransService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Checkout Aman - Yala Computer')]
class Checkout extends Component
{
    // Form Data
    public $name;
    public $phone;
    public $address;
    public $city;
    public $courier = 'jne'; // jne, jnt, sicepat
    public $pointsToRedeem = 0;
    
    // Voucher Logic
    public $voucherCode = '';
    public $appliedVoucher = null; // Voucher Model Instance
    public $voucherDiscount = 0;

    // Address Book Logic
    public $savedAddresses = [];
    public $selectedAddressId = null;

    // Computed Data
    public $cartItems = [];
    public $subtotal = 0;
    public $shippingCost = 0;
    public $discountAmount = 0;
    public $grandTotal = 0;

    public $cities = [
        'Jakarta' => 10000,
        'Bogor' => 15000,
        'Depok' => 15000,
        'Tangerang' => 15000,
        'Bekasi' => 15000,
        'Bandung' => 20000,
        'Surabaya' => 25000,
        'Semarang' => 30000,
        'Yogyakarta' => 30000,
        'Medan' => 35000,
        'Denpasar' => 45000,
        'Makassar' => 45000,
        'Lainnya' => 50000,
    ];

    public function mount()
    {
        $this->loadCart();
        if (empty($this->cartItems)) {
            return redirect()->route('home');
        }

        if (Auth::check()) {
            $this->savedAddresses = \App\Models\UserAddress::where('user_id', Auth::id())->get();
            $primary = $this->savedAddresses->where('is_primary', true)->first();
            if ($primary) {
                $this->selectAddress($primary->id);
            } else {
                $this->name = Auth::user()->name;
                $this->phone = Auth::user()->phone ?? '';
            }
        }
    }

    public function selectAddress($id)
    {
        $addr = $this->savedAddresses->where('id', $id)->first();
        if ($addr) {
            $this->selectedAddressId = $id;
            $this->name = $addr->recipient_name;
            $this->phone = $addr->phone_number;
            $this->address = $addr->address_line;
            $this->city = $addr->city;
            $this->calculateTotals();
        }
    }

    public function loadCart()
    {
        $cart = Session::get('cart', []);
        $this->cartItems = [];
        $this->subtotal = 0;

        if (!empty($cart)) {
            $products = Product::whereIn('id', array_keys($cart))->get();
            foreach ($products as $product) {
                $qty = $cart[$product->id];
                $lineTotal = $product->sell_price * $qty;
                
                $this->cartItems[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'line_total' => $lineTotal
                ];
                $this->subtotal += $lineTotal;
            }
        }
        $this->calculateTotals();
    }

    public function updatedCity() { $this->calculateTotals(); }

    public function applyVoucher()
    {
        $this->validate(['voucherCode' => 'required|string']);

        $voucher = Voucher::where('code', $this->voucherCode)->first();

        if (!$voucher) {
            $this->addError('voucherCode', 'Kode voucher tidak valid.');
            return;
        }

        // Validate Rules
        if (!$voucher->isValidForUser(Auth::id(), $this->subtotal)) {
            $this->addError('voucherCode', 'Voucher tidak dapat digunakan (Min. belanja / Kuota habis).');
            return;
        }

        $this->appliedVoucher = $voucher;
        $this->calculateTotals();
        $this->dispatch('notify', message: 'Voucher berhasil dipasang!', type: 'success');
    }

    public function removeVoucher()
    {
        $this->appliedVoucher = null;
        $this->voucherCode = '';
        $this->voucherDiscount = 0;
        $this->calculateTotals();
    }

    public function updatedPointsToRedeem()
    {
        if (!Auth::check()) {
            $this->pointsToRedeem = 0;
            return;
        }
        $maxPoints = Auth::user()->points;
        if ($this->pointsToRedeem > $maxPoints) $this->pointsToRedeem = $maxPoints;
        if ($this->pointsToRedeem < 0) $this->pointsToRedeem = 0;
        
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $totalWeight = 0;
        foreach ($this->cartItems as $item) {
            $w = $item['product']->weight > 0 ? $item['product']->weight : 1000;
            $totalWeight += ($w * $item['qty']);
        }
        $totalWeightKg = ceil($totalWeight / 1000);
        if ($totalWeightKg < 1) $totalWeightKg = 1;

        $baseCost = $this->cities[$this->city] ?? 0;
        $this->shippingCost = $baseCost * $totalWeightKg;
        
        // 1. Calculate Voucher Discount
        if ($this->appliedVoucher) {
            // Re-validate just in case subtotal changed below min spend
            if ($this->subtotal >= $this->appliedVoucher->min_spend) {
                $this->voucherDiscount = $this->appliedVoucher->calculateDiscount($this->subtotal);
            } else {
                // Auto remove if no longer valid
                $this->appliedVoucher = null; 
                $this->voucherDiscount = 0;
            }
        } else {
            $this->voucherDiscount = 0;
        }

        // 2. Points Discount
        $this->discountAmount = $this->pointsToRedeem;

        // 3. Grand Total (Subtotal + Shipping - Voucher - Points)
        $this->grandTotal = $this->subtotal + $this->shippingCost - $this->voucherDiscount - $this->discountAmount;
        if ($this->grandTotal < 0) $this->grandTotal = 0;
    }

    public function placeOrder(MidtransService $paymentService)
    {
        $this->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'courier' => 'required|string',
        ]);

        if (empty($this->cartItems)) return;

        $order = DB::transaction(function () {
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => Auth::id(),
                'guest_name' => $this->name,
                'guest_whatsapp' => $this->phone,
                'shipping_address' => $this->address,
                'shipping_city' => $this->city,
                'shipping_courier' => $this->courier,
                'shipping_cost' => $this->shippingCost,
                'points_redeemed' => $this->pointsToRedeem,
                'discount_amount' => $this->discountAmount, // Points Discount
                'voucher_code' => $this->appliedVoucher?->code,
                'voucher_discount' => $this->voucherDiscount,
                'total_amount' => $this->grandTotal,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => 'Checkout via Website',
            ]);

            // Save Voucher Usage
            if ($this->appliedVoucher) {
                \App\Models\VoucherUsage::create([
                    'voucher_id' => $this->appliedVoucher->id,
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                    'discount_amount' => $this->voucherDiscount,
                    'used_at' => now(),
                ]);
            }

            foreach ($this->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['qty'],
                    'price' => $item['product']->sell_price,
                ]);
                $item['product']->decrement('stock_quantity', $item['qty']);
            }

            if (Auth::check() && $this->pointsToRedeem > 0) {
                Auth::user()->decrement('points', $this->pointsToRedeem);
            }

            Session::forget('cart');
            return $order;
        });

        // Get Snap Token
        try {
            $snapData = $paymentService->getSnapToken($order);
            $order->update([
                'snap_token' => $snapData['token'],
                'payment_url' => $snapData['redirect_url']
            ]);

            $this->dispatch('trigger-payment', token: $snapData['token'], orderId: $order->id);
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Payment Gateway Error: ' . $e->getMessage(), type: 'error');
            // If failed, redirect to success page anyway (manual payment fallback)
            return redirect()->route('order.success', $order->id);
        }
    }

    public function render()
    {
        return view('livewire.store.checkout');
    }
}
