<?php

namespace App\Livewire\Transactions;

use App\Models\Customer;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Point of Sales - Yala Computer')]
class Create extends Component
{
    // Transaction Info
    public $type = 'out'; // Default to 'out' (Sales)
    public $customer_phone = '';
    public $reference_number = '';
    public $notes = '';
    
    // Cart System
    public $cart = []; // Array of ['product_id', 'name', 'price', 'quantity', 'subtotal', 'sku', 'image']

    // Search & Filter
    public $search = '';
    public $category = '';
    
    // UI State
    public $showSuccessModal = false;

    public function mount()
    {
        $this->reference_number = 'TRX-' . strtoupper(uniqid());
    }

    // --- Search & Barcode Logic ---
    public function updatedSearch()
    {
        // Barcode Scanning Simulation (Exact Match)
        $exactProduct = Product::where('sku', $this->search)
            ->orWhere('barcode', $this->search)
            ->first();

        if ($exactProduct) {
            $this->addToCart($exactProduct->id);
            $this->search = ''; // Reset after scan
            $this->dispatch('play-beep'); // Optional: Sound effect trigger
        }
    }

    // --- Cart Management ---
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) return;

        // Check if item exists in cart
        $existingItemKey = null;
        foreach ($this->cart as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingItemKey = $key;
                break;
            }
        }

        if ($existingItemKey !== null) {
            $this->cart[$existingItemKey]['quantity']++;
            $this->cart[$existingItemKey]['subtotal'] = $this->cart[$existingItemKey]['quantity'] * $this->cart[$existingItemKey]['price'];
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->sell_price,
                'buy_price' => $product->buy_price, // Hidden for calc
                'quantity' => 1,
                'image' => $product->image_path,
                'max_stock' => $product->stock_quantity,
                'subtotal' => $product->sell_price
            ];
        }
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); // Re-index
    }

    public function updateQty($index, $qty)
    {
        if (!isset($this->cart[$index])) return;

        $newQty = max(1, intval($qty));
        
        // Stock Check for Sales
        if ($this->type === 'out' && $newQty > $this->cart[$index]['max_stock']) {
            $this->dispatch('notify', message: 'Stok tidak mencukupi!', type: 'error');
            $newQty = $this->cart[$index]['max_stock'];
        }

        $this->cart[$index]['quantity'] = $newQty;
        $this->cart[$index]['subtotal'] = $newQty * $this->cart[$index]['price'];
    }

    // --- Calculations ---
    public function getSubtotalProperty()
    {
        return array_sum(array_column($this->cart, 'subtotal'));
    }

    public function getTaxProperty()
    {
        // Simple 11% PPN logic (Optional, configurable)
        return 0; // Keeping it 0 for now as per Indonesian simple store logic
    }

    public function getTotalProperty()
    {
        return $this->subtotal + $this->tax;
    }

    // --- Finalize Transaction ---
    public function save()
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', message: 'Keranjang kosong!', type: 'error');
            return;
        }

        $this->validate([
            'type' => 'required|in:in,out,adjustment,return',
            'notes' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () {
            foreach ($this->cart as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                // Final Stock Check
                if ($this->type === 'out' && $product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi saat proses akhir.");
                }

                // Update Stock
                $newStock = $product->stock_quantity;
                if ($this->type === 'in' || $this->type === 'return') {
                    $newStock += $item['quantity'];
                } else {
                    $newStock -= $item['quantity'];
                }
                
                $product->update(['stock_quantity' => $newStock]);

                // Create Log
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id() ?? 1,
                    'type' => $this->type,
                    'quantity' => $item['quantity'],
                    'remaining_stock' => $newStock,
                    'reference_number' => $this->reference_number,
                    'notes' => $this->notes . ($this->customer_phone ? " (Member: {$this->customer_phone})" : ''),
                ]);

                // Member Points Logic
                if ($this->type === 'out' && $this->customer_phone) {
                    $customer = Customer::firstOrCreate(
                        ['phone' => $this->customer_phone],
                        ['name' => 'Member ' . $this->customer_phone, 'email' => $this->customer_phone.'@member.com']
                    );
                    
                    $pointsEarned = floor($item['subtotal'] / 100000);
                    if ($pointsEarned > 0) {
                        $customer->increment('points', $pointsEarned);
                    }
                }
            }
        });

        $this->cart = [];
        $this->reference_number = 'TRX-' . strtoupper(uniqid());
        $this->customer_phone = '';
        $this->notes = '';
        
        $this->dispatch('notify', message: 'Transaksi berhasil disimpan!');
    }

    public function render()
    {
        $productsQuery = Product::query()
            ->where('is_active', true)
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function($q) {
                $q->where('category_id', $this->category);
            });

        return view('livewire.transactions.create', [
            'products' => $productsQuery->paginate(12),
            'categories' => \App\Models\Category::all()
        ]);
    }
}