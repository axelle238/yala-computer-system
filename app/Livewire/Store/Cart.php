<?php

namespace App\Livewire\Store;

use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Keranjang Belanja - Yala Computer')]
class Cart extends Component
{
    public $cart = []; // [id => qty]

    public $cartProducts = []; // Collection of Product models

    public $subtotal = 0;

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->cart = session()->get('cart', []);
        $this->cartProducts = collect();
        $this->subtotal = 0;

        if (! empty($this->cart)) {
            // Filter out non-integer keys just in case
            $ids = array_filter(array_keys($this->cart), 'is_numeric');

            $products = Product::whereIn('id', $ids)->get();

            foreach ($products as $product) {
                // Determine Quantity
                $qty = $this->cart[$product->id];
                // Handle complex structure if legacy exists (migration path)
                if (is_array($qty)) {
                    $qty = $qty['quantity'] ?? 1;
                    // Auto-fix session
                    $this->cart[$product->id] = $qty;
                }

                $product->cart_qty = intval($qty);
                $product->line_total = $product->sell_price * $product->cart_qty;

                $this->cartProducts->push($product);
                $this->subtotal += $product->line_total;
            }

            // Save standardized cart back to session
            session()->put('cart', $this->cart);
        }
    }

    public function updateQuantity($productId, $qty)
    {
        if ($qty < 1) {
            $this->removeItem($productId);

            return;
        }

        $this->cart = session()->get('cart', []);
        $this->cart[$productId] = intval($qty);
        session()->put('cart', $this->cart);

        $this->refreshCart();
        $this->dispatch('cart-updated');
    }

    public function removeItem($productId)
    {
        $this->cart = session()->get('cart', []);
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
            session()->put('cart', $this->cart);
            $this->refreshCart();
            $this->dispatch('cart-updated');
        }
    }

    public function requestQuote()
    {
        if (! Auth::check()) {
            $this->dispatch('notify', message: 'Silakan login untuk meminta penawaran.', type: 'warning');

            return redirect()->route('login');
        }

        if ($this->cartProducts->isEmpty()) {
            $this->dispatch('notify', message: 'Keranjang kosong.', type: 'error');

            return;
        }

        DB::transaction(function () {
            $quote = Quotation::create([
                'quotation_number' => 'Q-'.date('Ymd').'-'.strtoupper(Str::random(4)),
                'user_id' => Auth::id(),
                'status' => 'pending', // Draft/Submitted
                'approval_status' => 'pending',
                'total_amount' => $this->subtotal,
                'valid_until' => now()->addDays(7), // Default 7 days validity
                'notes' => 'Generated from Web Cart',
            ]);

            foreach ($this->cartProducts as $product) {
                QuotationItem::create([
                    'quotation_id' => $quote->id,
                    'product_id' => $product->id,
                    'item_name' => $product->name,
                    'quantity' => $product->cart_qty,
                    'unit_price' => $product->sell_price,
                    'total_price' => $product->line_total,
                ]);
            }

            // Empty Cart
            session()->forget('cart');
        });

        $this->dispatch('notify', message: 'Permintaan penawaran berhasil dikirim!', type: 'success');

        return redirect()->route('member.orders'); // Redirect to Member Area (Needs Quotation Tab)
    }

    public function render()
    {
        return view('livewire.store.cart');
    }
}
