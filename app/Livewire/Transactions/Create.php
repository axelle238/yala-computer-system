<?php

namespace App\Livewire\Transactions;

use App\Models\Commission;
use App\Models\Customer;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;
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
            $this->updateQty($existingItemKey, $this->cart[$existingItemKey]['quantity'] + 1);
        } else {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->sell_price,
                'buy_price' => $product->buy_price, 
                'quantity' => 1,
                'image' => $product->image_path,
                'max_stock' => $product->stock_quantity,
                'subtotal' => $product->sell_price,
                'warranty_period' => $product->warranty_period ?? 0, // e.g., 12 months
                'serial_numbers' => [''] // Init 1 empty SN
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

        // Adjust Serial Numbers array size
        $currentSNs = $this->cart[$index]['serial_numbers'] ?? [];
        if ($newQty > count($currentSNs)) {
            // Add empty slots
            for ($i = count($currentSNs); $i < $newQty; $i++) {
                $currentSNs[] = '';
            }
        } elseif ($newQty < count($currentSNs)) {
            // Remove excess slots
            $currentSNs = array_slice($currentSNs, 0, $newQty);
        }
        $this->cart[$index]['serial_numbers'] = $currentSNs;
    }
    
    // Helper to sync input from view
    public function updateSerial($index, $snIndex, $value)
    {
        $this->cart[$index]['serial_numbers'][$snIndex] = $value;
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
            // 1. Create Order Record (Only for Sales 'out')
            $order = null;
            if ($this->type === 'out') {
                $order = Order::create([
                    'user_id' => Auth::id(), // Sales/Cashier
                    'guest_name' => $this->customer_phone ? 'Member ' . $this->customer_phone : 'Guest',
                    'guest_whatsapp' => $this->customer_phone,
                    'order_number' => $this->reference_number,
                    'total_amount' => $this->total,
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'notes' => $this->notes,
                ]);
            }

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

                // Prepare Serial Numbers (Clean empty ones)
                $snList = array_filter($item['serial_numbers'] ?? []);
                $snString = !empty($snList) ? implode(',', $snList) : null;

                // Create Inventory Log
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id() ?? 1,
                    'type' => $this->type,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->sell_price, 
                    'cogs' => $product->buy_price,        
                    'remaining_stock' => $newStock,
                    'reference_number' => $this->reference_number,
                    'serial_numbers' => $this->type === 'out' ? $snString : null,
                    'notes' => $this->notes . ($this->customer_phone ? " (Member: {$this->customer_phone})" : ''),
                ]);

                // Create Order Item (if Order exists)
                if ($order) {
                    // Calculate Warranty Expiry
                    $warrantyEnds = null;
                    if (!empty($item['warranty_period']) && $item['warranty_period'] > 0) {
                        $warrantyEnds = Carbon::now()->addMonths($item['warranty_period']);
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->sell_price,
                        'serial_numbers' => $snString,
                        'warranty_ends_at' => $warrantyEnds
                    ]);
                }

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

            // 2. Automated Sales Commission
            if ($order && Auth::check()) {
                $percent = Setting::get('commission_sales_percent', 1); // Default 1%
                $commissionAmount = $order->total_amount * ($percent / 100);

                if ($commissionAmount > 0) {
                    Commission::create([
                        'user_id' => Auth::id(),
                        'amount' => $commissionAmount,
                        'description' => "Komisi Penjualan #{$order->order_number} ({$percent}%)",
                        'source_type' => Order::class,
                        'source_id' => $order->id,
                        'is_paid' => false
                    ]);
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