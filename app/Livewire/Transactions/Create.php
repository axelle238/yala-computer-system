<?php

namespace App\Livewire\Transactions;

use App\Models\CashRegister;
use App\Models\Commission;
use App\Models\Customer;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductBundle;
use App\Models\Setting;
use App\Models\SavedBuild;
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
    public $searchBuild = ''; 

    // Loyalty System
    public $customerPoints = 0;
    public $usePoints = false;
    
    // UI State
    public $showSuccessModal = false;
    public $registerStatus = 'closed'; // open, closed

    public function mount()
    {
        $this->reference_number = 'TRX-' . strtoupper(uniqid());
        $this->checkRegister();
    }

    public function checkRegister()
    {
        $active = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->exists();
        
        $this->registerStatus = $active ? 'open' : 'closed';

        if (!$active && $this->type === 'out') {
            // Optional: Redirect immediately or show blocker
            // return redirect()->route('finance.cash-register');
        }
    }

    // --- Saved Build Integration ---
    public function loadBuild()
    {
        $build = SavedBuild::where('id', $this->searchBuild)
            ->orWhere('share_token', $this->searchBuild)
            ->first();

        if (!$build) {
            $this->dispatch('notify', message: 'Rakitan tidak ditemukan.', type: 'error');
            return;
        }

        $components = $build->components; 
        $loadedCount = 0;
        
        foreach ($components as $key => $productId) {
            if ($productId) {
                $product = Product::find($productId);
                if ($product && $product->stock_quantity > 0) {
                    $this->addToCart($product->id);
                    $loadedCount++;
                }
            }
        }

        if ($loadedCount > 0) {
            $this->dispatch('notify', message: "Berhasil memuat {$loadedCount} komponen dari rakitan '{$build->name}'.", type: 'success');
            $this->searchBuild = ''; 
        } else {
            $this->dispatch('notify', message: 'Semua komponen dalam rakitan ini stoknya habis.', type: 'error');
        }
    }

    // --- Customer & Loyalty Logic ---
    public function updatedCustomerPhone()
    {
        if (strlen($this->customer_phone) >= 10) {
            $customer = Customer::where('phone', $this->customer_phone)->first();
            if ($customer) {
                $this->customerPoints = $customer->points;
                $this->dispatch('notify', message: "Member ditemukan! Poin: " . number_format($customer->points), type: 'info');
            } else {
                $this->customerPoints = 0;
            }
        } else {
            $this->customerPoints = 0;
            $this->usePoints = false;
        }
    }

    public function togglePoints()
    {
        if ($this->customerPoints <= 0) return;
        $this->usePoints = !$this->usePoints;
    }

    // --- Search & Barcode Logic ---
    public function updatedSearch()
    {
        $exactProduct = Product::where('sku', $this->search)
            ->orWhere('barcode', $this->search)
            ->first();

        if ($exactProduct) {
            $this->addToCart($exactProduct->id);
            $this->search = ''; 
            $this->dispatch('play-beep');
        }
    }

    // --- Cart Management ---
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) return;

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
                'warranty_period' => $product->warranty_period ?? 0, 
                'serial_numbers' => [''] 
            ];
        }
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); 
    }

    public function updateQty($index, $qty)
    {
        if (!isset($this->cart[$index])) return;

        $newQty = max(1, intval($qty));
        
        if ($this->type === 'out' && $newQty > $this->cart[$index]['max_stock']) {
            $this->dispatch('notify', message: 'Stok tidak mencukupi!', type: 'error');
            $newQty = $this->cart[$index]['max_stock'];
        }

        $this->cart[$index]['quantity'] = $newQty;
        $this->cart[$index]['subtotal'] = $newQty * $this->cart[$index]['price'];

        $currentSNs = $this->cart[$index]['serial_numbers'] ?? [];
        if ($newQty > count($currentSNs)) {
            for ($i = count($currentSNs); $i < $newQty; $i++) {
                $currentSNs[] = '';
            }
        } elseif ($newQty < count($currentSNs)) {
            $currentSNs = array_slice($currentSNs, 0, $newQty);
        }
        $this->cart[$index]['serial_numbers'] = $currentSNs;
    }
    
    public function updateSerial($index, $snIndex, $value)
    {
        $this->cart[$index]['serial_numbers'][$snIndex] = $value;
    }

    // --- Calculations ---
    public function getSubtotalProperty()
    {
        return array_sum(array_column($this->cart, 'subtotal'));
    }

    public function getDiscountProperty()
    {
        if ($this->usePoints && $this->customerPoints > 0) {
            return min($this->customerPoints, $this->subtotal);
        }
        return 0;
    }

    public function getTaxProperty()
    {
        return 0; 
    }

    public function getTotalProperty()
    {
        return max(0, $this->subtotal + $this->tax - $this->discount);
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

        $activeRegister = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (!$activeRegister && $this->type === 'out') {
            $this->dispatch('notify', message: 'Shift Kasir belum dibuka!', type: 'error');
            return redirect()->route('shift.open');
        }

        if ($this->usePoints && $this->customerPoints > 0) {
            $customer = Customer::where('phone', $this->customer_phone)->first();
            if (!$customer || $customer->points < $this->discount) {
                $this->addError('customer_phone', 'Saldo poin tidak valid atau berubah.');
                return;
            }
        }

        DB::transaction(function () use ($activeRegister) {
            $order = null;
            if ($this->type === 'out') {
                $notes = $this->notes;
                if ($this->discount > 0) {
                    $notes .= " [Redeem {$this->discount} Points]";
                }

                $order = Order::create([
                    'user_id' => Auth::id(), 
                    'cash_register_id' => $activeRegister->id,
                    'guest_name' => $this->customer_phone ? 'Member ' . $this->customer_phone : 'Guest',
                    'guest_whatsapp' => $this->customer_phone,
                    'order_number' => $this->reference_number,
                    'total_amount' => $this->total, 
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => 'cash',
                    'notes' => $notes,
                ]);

                if ($this->discount > 0 && $this->customer_phone) {
                    $customer = Customer::where('phone', $this->customer_phone)->first();
                    $customer->decrement('points', $this->discount);
                }
            }

            foreach ($this->cart as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                if (!$product) continue;

                $quantitySold = $item['quantity'];

                // --- COMPLEX BUNDLE LOGIC ---
                if ($product->is_bundle) {
                    $components = ProductBundle::where('parent_product_id', $product->id)->get();
                    
                    if ($components->isEmpty()) {
                        $this->processStockDeduction($product, $quantitySold, $activeRegister, $order);
                    } else {
                        foreach ($components as $component) {
                            $childProduct = Product::lockForUpdate()->find($component->child_product_id);
                            if ($childProduct) {
                                $neededQty = $component->quantity * $quantitySold;
                                $this->processStockDeduction($childProduct, $neededQty, $activeRegister, $order, "Bundle: {$product->name}");
                            }
                        }
                    }
                } else {
                    $this->processStockDeduction($product, $quantitySold, $activeRegister, $order);
                }

                // Prepare Serial Numbers (Clean empty ones)
                $snList = array_filter($item['serial_numbers'] ?? []);
                $snString = !empty($snList) ? implode(',', $snList) : null;

                // Create Order Item
                if ($order) {
                    $warrantyEnds = null;
                    if (!empty($item['warranty_period']) && $item['warranty_period'] > 0) {
                        $warrantyEnds = Carbon::now()->addMonths($item['warranty_period']);
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'serial_numbers' => $snString,
                        'warranty_ends_at' => $warrantyEnds
                    ]);
                }

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

            if ($order && Auth::check()) {
                $percent = Setting::get('commission_sales_percent', 1); 
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

    protected function processStockDeduction($product, $qty, $register, $order, $notePrefix = '')
    {
        if ($this->type === 'out' && $product->stock_quantity < $qty) {
            throw new \Exception("Stok {$product->name} tidak mencukupi! Butuh: $qty, Sisa: {$product->stock_quantity}");
        }

        $newStock = $product->stock_quantity;
        if ($this->type === 'in' || $this->type === 'return') {
            $newStock += $qty;
        } else {
            $newStock -= $qty;
        }
        
        $product->update(['stock_quantity' => $newStock]);

        InventoryTransaction::create([
            'product_id' => $product->id,
            'user_id' => Auth::id() ?? 1,
            'type' => $this->type,
            'quantity' => $qty,
            'unit_price' => $product->sell_price, 
            'cogs' => $product->buy_price,        
            'remaining_stock' => $newStock,
            'reference_number' => $this->reference_number,
            'notes' => trim($notePrefix . ' ' . $this->notes),
        ]);
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
