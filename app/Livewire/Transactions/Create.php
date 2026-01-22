<?php

namespace App\Livewire\Transactions;

use App\Models\Customer;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Catat Transaksi - Yala Computer')]
class Create extends Component
{
    public $product_id = '';
    public $type = 'out'; // Default to 'out' (Sales)
    public $quantity = 1;
    public $notes = '';
    public $reference_number = '';
    public $customer_phone = ''; 
    public $serial_numbers = ''; // New

    // Searchable Product Dropdown
    public $productSearch = '';
    public $products = [];

    public function mount()
    {
        // Initial load of some products or empty
        $this->products = Product::where('is_active', true)->limit(20)->get();
    }

    public function updatedProductSearch()
    {
        $this->products = Product::where('is_active', true)
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->productSearch . '%')
                  ->orWhere('sku', 'like', '%' . $this->productSearch . '%');
            })
            ->limit(20)
            ->get();
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment,return',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:50',
            'customer_phone' => 'nullable|string',
        ]);

        $product = Product::findOrFail($this->product_id);

        // Validation for Outgoing Stock
        if ($this->type === 'out' && $product->stock_quantity < $this->quantity) {
            $this->addError('quantity', "Stok tidak mencukupi! Stok saat ini: {$product->stock_quantity}");
            return;
        }

        DB::transaction(function () use ($product) {
            // 1. Calculate new stock
            $newStock = $product->stock_quantity;
            if ($this->type === 'in' || $this->type === 'return') {
                $newStock += $this->quantity;
            } else {
                $newStock -= $this->quantity;
            }

            // 2. Create Transaction Record
            InventoryTransaction::create([
                'product_id' => $product->id,
                'user_id' => Auth::id() ?? 1,
                'type' => $this->type,
                'quantity' => $this->quantity,
                'serial_numbers' => $this->serial_numbers, // Save SN
                'remaining_stock' => $newStock,
                'reference_number' => $this->reference_number,
                'notes' => $this->notes . ($this->customer_phone ? " (Member: {$this->customer_phone})" : ''),
            ]);

            // 3. Update Product Stock
            $product->update(['stock_quantity' => $newStock]);

            // 4. Update Member Points (Jika Transaksi Keluar & Ada Member)
            if ($this->type === 'out' && $this->customer_phone) {
                $customer = Customer::where('phone', $this->customer_phone)->first();
                if ($customer) {
                    $totalPrice = $product->sell_price * $this->quantity;
                    $pointsEarned = floor($totalPrice / 100000); // 1 Poin per 100rb
                    if ($pointsEarned > 0) {
                        $customer->increment('points', $pointsEarned);
                    }
                }
            }
        });

        session()->flash('success', 'Transaksi berhasil dicatat!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.transactions.create');
    }
}