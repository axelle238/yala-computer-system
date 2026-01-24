<?php

namespace App\Livewire\Store;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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

    // Computed Data
    public $cartItems = [];
    public $subtotal = 0;
    public $shippingCost = 0;
    public $discountAmount = 0;
    public $grandTotal = 0;

    // Mock Cities (In real app, fetch from RajaOngkir)
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
            $this->name = Auth::user()->name;
            $this->phone = Auth::user()->phone ?? ''; // Assuming phone exists or add later
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

    public function updatedCity()
    {
        $this->calculateTotals();
    }

    public function updatedPointsToRedeem()
    {
        if (!Auth::check()) {
            $this->pointsToRedeem = 0;
            return;
        }

        $maxPoints = Auth::user()->points;
        if ($this->pointsToRedeem > $maxPoints) {
            $this->pointsToRedeem = $maxPoints;
        }
        if ($this->pointsToRedeem < 0) {
            $this->pointsToRedeem = 0;
        }
        
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        // 1. Shipping
        $baseCost = $this->cities[$this->city] ?? 0;
        // Mock weight multiplier: assume standard 1kg package for simplicity or sum weights if available
        $this->shippingCost = $baseCost;

        // 2. Discount (1 Point = Rp 1)
        $this->discountAmount = $this->pointsToRedeem;

        // 3. Grand Total
        $this->grandTotal = $this->subtotal + $this->shippingCost - $this->discountAmount;
        if ($this->grandTotal < 0) $this->grandTotal = 0;
    }

    public function placeOrder()
    {
        $this->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'courier' => 'required|string',
        ]);

        if (empty($this->cartItems)) return;

        DB::transaction(function () {
            // 1. Create Order
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => Auth::id(), // Nullable if guest
                'guest_name' => $this->name,
                'guest_whatsapp' => $this->phone,
                'shipping_address' => $this->address,
                'shipping_city' => $this->city,
                'shipping_courier' => $this->courier,
                'shipping_cost' => $this->shippingCost,
                'points_redeemed' => $this->pointsToRedeem,
                'discount_amount' => $this->discountAmount,
                'total_amount' => $this->grandTotal,
                'status' => 'pending', // Pending Payment
                'payment_status' => 'unpaid',
                'notes' => 'Checkout via Website',
            ]);

            // 2. Create Items & Deduct Stock
            foreach ($this->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['qty'],
                    'price' => $item['product']->sell_price,
                ]);

                // Deduct Stock
                $item['product']->decrement('stock_quantity', $item['qty']);
            }

            // 3. Deduct User Points
            if (Auth::check() && $this->pointsToRedeem > 0) {
                Auth::user()->decrement('points', $this->pointsToRedeem);
            }

            // 4. Award Points (Earn 1% of total)
            if (Auth::check()) {
                $pointsEarned = floor($this->subtotal * 0.01);
                // Can be added immediately or after payment confirmed. 
                // Let's add immediately but maybe logic elsewhere better. 
                // For safety, let's NOT add earned points yet, waiting for 'completed' status.
            }

            // 5. Clear Cart
            Session::forget('cart');
            
            // Redirect to Success/Payment
            // For now, redirect to Order Success page
            return redirect()->route('order.success', $order->id);
        });

        return redirect()->route('home'); // Fallback
    }

    public function render()
    {
        return view('livewire.store.checkout');
    }
}