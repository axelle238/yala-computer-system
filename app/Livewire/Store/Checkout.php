<?php

namespace App\Livewire\Store;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherUsage;
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
    public $paymentMethod = 'midtrans'; // midtrans, transfer_manual (Visual logic)
    public $orderNotes = '';
    
    // Voucher Logic
    public $voucherCode = '';
    public $appliedVoucher = null; 
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

    // Static Data
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

    public function clearAddressSelection()
    {
        $this->selectedAddressId = null;
        $this->name = Auth::check() ? Auth::user()->name : '';
        $this->phone = Auth::check() ? (Auth::user()->phone ?? '') : '';
        $this->address = '';
        $this->city = '';
        $this->calculateTotals();
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
    public function updatedCourier() { $this->calculateTotals(); }

    public function applyVoucher()
    {
        $this->validate(['voucherCode' => 'required|string']);

        $voucher = Voucher::where('code', $this->voucherCode)->first();

        if (!$voucher) {
            $this->addError('voucherCode', 'Kode voucher tidak valid.');
            return;
        }

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
        // 1. Shipping
        $totalWeight = 0;
        foreach ($this->cartItems as $item) {
            $w = $item['product']->weight > 0 ? $item['product']->weight : 1000;
            $totalWeight += ($w * $item['qty']);
        }
        $totalWeightKg = ceil($totalWeight / 1000);
        if ($totalWeightKg < 1) $totalWeightKg = 1;

        $baseCost = $this->cities[$this->city] ?? 0;
        $this->shippingCost = $baseCost * $totalWeightKg;
        
        // 2. Voucher
        if ($this->appliedVoucher) {
            if ($this->subtotal >= $this->appliedVoucher->min_spend) {
                $this->voucherDiscount = $this->appliedVoucher->calculateDiscount($this->subtotal);
            } else {
                $this->appliedVoucher = null; 
                $this->voucherDiscount = 0;
            }
        } else {
            $this->voucherDiscount = 0;
        }

        // 3. Points
        $this->discountAmount = $this->pointsToRedeem;

        // 4. Grand Total
        $this->grandTotal = $this->subtotal + $this->shippingCost - $this->voucherDiscount - $this->discountAmount;
        if ($this->grandTotal < 0) $this->grandTotal = 0;
    }

    public function placeOrder(MidtransService $paymentService)
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'phone' => 'required|string|min:8',
            'address' => 'required|string|min:10',
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
                'discount_amount' => $this->discountAmount,
                'voucher_code' => $this->appliedVoucher?->code,
                'voucher_discount' => $this->voucherDiscount,
                'total_amount' => $this->grandTotal,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => $this->orderNotes ?? 'Checkout via Website',
            ]);

            if ($this->appliedVoucher) {
                VoucherUsage::create([
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

            // Handle PC Assembly Request
            if (Session::has('pc_assembly_data')) {
                $assemblyData = Session::get('pc_assembly_data');
                
                \App\Models\PcAssembly::create([
                    'order_id' => $order->id,
                    'build_name' => $assemblyData['build_name'],
                    'status' => 'queued',
                    'specs_snapshot' => json_encode($assemblyData['specs']),
                ]);

                Session::forget('pc_assembly_data');
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
            $this->dispatch('notify', message: 'Payment Error: ' . $e->getMessage(), type: 'error');
            return redirect()->route('order.success', $order->id);
        }
    }

    public function render()
    {
        return view('livewire.store.checkout');
    }
}