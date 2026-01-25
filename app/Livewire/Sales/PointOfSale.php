<?php

namespace App\Livewire\Sales;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')] // Menggunakan layout admin, tapi nanti kita buat full-width di view
#[Title('Point of Sales (POS)')]
class PointOfSale extends Component
{
    // Search & Filter
    public $searchQuery = '';

    public $categoryId = null;

    // Cart
    public $cart = []; // [product_id => [id, name, price, qty, subtotal, stock]]

    public $subtotal = 0;

    public $discount = 0;

    public $grandTotal = 0;

    // Customer
    public $selectedMemberId = null;

    public $guestName = 'Tamu';

    public $memberSearch = '';

    public $searchResultsMember = [];

    // Payment
    public $paymentMethod = 'cash'; // cash, transfer, qris

    public $cashGiven = 0;

    public $change = 0;

    // System State
    public $activeRegister;

    public function mount()
    {
        $this->checkRegister();
    }

    public function checkRegister()
    {
        $this->activeRegister = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        if (! $this->activeRegister) {
            // Redirect jika belum buka kasir
            return redirect()->route('finance.cash-register')->with('error', 'Silakan buka shift kasir terlebih dahulu.');
        }
    }

    // --- Product Logic ---

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (! $product) {
            return;
        }
        if ($product->stock_quantity <= 0) {
            $this->dispatch('notify', message: 'Stok habis!', type: 'error');

            return;
        }

        if (isset($this->cart[$productId])) {
            // Cek stok sebelum nambah
            if ($this->cart[$productId]['qty'] + 1 > $product->stock_quantity) {
                $this->dispatch('notify', message: 'Stok tidak mencukupi!', type: 'error');

                return;
            }
            $this->cart[$productId]['qty']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->sell_price,
                'qty' => 1,
                'max_stock' => $product->stock_quantity,
            ];
        }

        $this->calculateTotals();
        $this->searchQuery = ''; // Reset search focus
    }

    public function updateQty($productId, $qty)
    {
        if (! isset($this->cart[$productId])) {
            return;
        }

        $qty = intval($qty);

        if ($qty <= 0) {
            unset($this->cart[$productId]);
        } else {
            // Validasi Stok
            if ($qty > $this->cart[$productId]['max_stock']) {
                $this->dispatch('notify', message: 'Melebihi stok tersedia!', type: 'error');
                $qty = $this->cart[$productId]['max_stock'];
            }
            $this->cart[$productId]['qty'] = $qty;
        }

        $this->calculateTotals();
    }

    public function removeItem($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        foreach ($this->cart as $item) {
            $this->subtotal += $item['price'] * $item['qty'];
        }

        // Diskon logic (bisa dikembangkan)
        $this->grandTotal = max(0, $this->subtotal - $this->discount);
        $this->calculateChange();
    }

    public function updatedCashGiven()
    {
        $this->calculateChange();
    }

    public function calculateChange()
    {
        if ($this->paymentMethod == 'cash') {
            $this->change = max(0, floatval($this->cashGiven) - $this->grandTotal);
        } else {
            $this->change = 0;
            $this->cashGiven = $this->grandTotal; // Auto fill for non-cash
        }
    }

    // --- Member Logic ---

    public function updatedMemberSearch()
    {
        if (strlen($this->memberSearch) > 2) {
            $this->searchResultsMember = User::where('name', 'like', '%'.$this->memberSearch.'%')
                ->orWhere('email', 'like', '%'.$this->memberSearch.'%')
                ->orWhere('phone', 'like', '%'.$this->memberSearch.'%')
                ->limit(5)
                ->get();
        } else {
            $this->searchResultsMember = [];
        }
    }

    public function selectMember($id)
    {
        $member = User::find($id);
        $this->selectedMemberId = $id;
        $this->guestName = $member->name;
        $this->memberSearch = '';
        $this->searchResultsMember = [];
    }

    // --- Checkout Logic ---

    public function processCheckout()
    {
        // 1. Validasi
        if (empty($this->cart)) {
            $this->dispatch('notify', message: 'Keranjang belanja kosong!', type: 'error');

            return;
        }

        if ($this->paymentMethod == 'cash' && $this->cashGiven < $this->grandTotal) {
            $this->dispatch('notify', message: 'Uang pembayaran kurang!', type: 'error');

            return;
        }

        // Cek ulang stok (untuk mencegah race condition sederhana)
        foreach ($this->cart as $id => $item) {
            $product = Product::find($id);
            if ($product->stock_quantity < $item['qty']) {
                $this->dispatch('notify', message: "Stok {$product->name} berubah/tidak cukup!", type: 'error');

                return;
            }
        }

        DB::transaction(function () {
            // 2. Buat Order
            $order = Order::create([
                'order_number' => 'ORD-'.date('ymd').'-'.rand(1000, 9999),
                'cash_register_id' => $this->activeRegister->id,
                'user_id' => $this->selectedMemberId, // Null if guest
                'guest_name' => $this->selectedMemberId ? null : $this->guestName,
                'total_amount' => $this->grandTotal,
                'discount_amount' => $this->discount,
                'payment_method' => $this->paymentMethod,
                'payment_status' => 'paid',
                'status' => 'completed', // Langsung selesai karena POS (barang dibawa)
                'paid_at' => now(),
            ]);

            foreach ($this->cart as $id => $item) {
                $product = Product::find($id);

                // 3. Buat Order Item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);

                // 4. Potong Stok & Log Inventory
                $product->decrement('stock_quantity', $item['qty']);

                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'warehouse_id' => 1,
                    'type' => 'out', // Sales
                    'quantity' => $item['qty'],
                    'remaining_stock' => $product->stock_quantity,
                    'unit_price' => $item['price'], // Selling Price
                    'cogs' => $product->buy_price, // HPP
                    'reference_number' => $order->order_number,
                    'notes' => 'POS Transaction',
                ]);
            }

            // 5. Catat Uang Masuk ke Kasir
            // Hanya jika Cash atau metode lain yang masuk ke laci kasir (opsional logic)
            // Biasanya Transfer masuk Bank, bukan Laci. Tapi untuk pencatatan Sales tetap masuk.
            // Kita catat semua sebagai Sales, tapi mungkin perlu flag 'cash_drawer' true/false.
            // Untuk simplifikasi, kita catat semua di CashTransaction dengan tipe metode.

            CashTransaction::create([
                'cash_register_id' => $this->activeRegister->id,
                'transaction_number' => 'TRX-POS-'.$order->id,
                'type' => 'in',
                'category' => 'sales',
                'amount' => $this->grandTotal,
                'description' => "Penjualan POS #{$order->order_number} ({$this->paymentMethod})",
                'reference_id' => $order->id,
                'reference_type' => Order::class,
                'created_by' => Auth::id(),
            ]);

            // Trigger Print Receipt
            $this->dispatch('print-receipt', orderId: $order->id);
        });

        // 6. Reset
        $this->reset(['cart', 'subtotal', 'discount', 'grandTotal', 'cashGiven', 'change', 'selectedMemberId']);
        $this->guestName = 'Tamu';
        session()->flash('success', 'Transaksi Berhasil!');
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->searchQuery, function ($q) {
                $q->where('name', 'like', '%'.$this->searchQuery.'%')
                    ->orWhere('sku', 'like', '%'.$this->searchQuery.'%');
            })
            ->when($this->categoryId, function ($q) {
                $q->where('category_id', $this->categoryId);
            })
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', '!=', 'services'); // Hanya produk fisik
            })
            ->latest()
            ->paginate(12);

        $categories = \App\Models\Category::all();

        return view('livewire.sales.point-of-sale', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
