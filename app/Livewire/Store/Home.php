<?php

namespace App\Livewire\Store;

use App\Models\Banner;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.store')]
#[Title('Yala Computer - Pusat Belanja IT')]
class Home extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $category = '';

    #[Url(history: true)]
    public $minPrice = 0;

    #[Url(history: true)]
    public $maxPrice = 50000000; // Default max 50jt

    #[Url(history: true)]
    public $sort = 'latest'; // latest, price_asc, price_desc

    // Modal State
    public $selectedProduct = null;
    public $showModal = false;

    // Cart State
    public $isCartOpen = false;
    public $cart = []; // [product_id => quantity]

    public function mount()
    {
        $this->cart = Session::get('cart', []);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    // --- Modal Logic ---
    public function openProduct($id)
    {
        $this->selectedProduct = Product::with('category', 'supplier')->find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedProduct = null;
    }

    // --- Cart Logic ---
    public function toggleCart()
    {
        $this->isCartOpen = !$this->isCartOpen;
    }

    public function addToCart($productId, $qty = 1)
    {
        $product = Product::find($productId);
        
        if (!$product || $product->stock_quantity < 1) {
            $this->dispatch('notify', message: 'Stok barang habis!');
            return;
        }

        if (isset($this->cart[$productId])) {
            $newQty = $this->cart[$productId] + $qty;
            if ($newQty > $product->stock_quantity) {
                 $this->dispatch('notify', message: 'Stok tidak mencukupi!');
                 return;
            }
            $this->cart[$productId] = $newQty;
        } else {
            $this->cart[$productId] = $qty;
        }

        Session::put('cart', $this->cart);
        $this->dispatch('notify', message: 'Produk masuk keranjang!');
        
        // Close modal if adding from modal
        if ($this->showModal) {
            $this->closeModal();
        }
        
        // Open cart to show user
        $this->isCartOpen = true;
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        Session::put('cart', $this->cart);
    }

    public function updateCartQty($productId, $change)
    {
        if (!isset($this->cart[$productId])) return;

        $newQty = $this->cart[$productId] + $change;
        $product = Product::find($productId);

        if ($newQty > 0 && $newQty <= $product->stock_quantity) {
            $this->cart[$productId] = $newQty;
            Session::put('cart', $this->cart);
        } elseif ($newQty <= 0) {
            $this->removeFromCart($productId);
        } else {
            $this->dispatch('notify', message: 'Stok maksimal tercapai!');
        }
    }

    public function checkout()
    {
        if (empty($this->cart)) return;

        $message = "Halo Yala Computer, saya ingin memesan:\n\n";
        $total = 0;
        
        $products = Product::whereIn('id', array_keys($this->cart))->get();

        foreach ($products as $product) {
            $qty = $this->cart[$product->id];
            $subtotal = $product->sell_price * $qty;
            $total += $subtotal;
            
            $message .= "- {$product->name} (x{$qty}) : Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }

        $message .= "\nTotal: *Rp " . number_format($total, 0, ',', '.') . "*";
        $message .= "\n\nMohon info ketersediaan dan ongkirnya. Terima kasih!";

        // Encode message for URL
        $encodedMessage = urlencode($message);
        
        // Get WhatsApp number from Settings
        $waNumber = Setting::get('whatsapp_number', '6281234567890');
        
        // Redirect to WhatsApp
        $waLink = "https://wa.me/{$waNumber}?text={$encodedMessage}";
        
        // Reset Cart after checkout intent (optional, but keep for now in case they come back)
        // Session::forget('cart'); 

        return redirect()->away($waLink);
    }

    public function getCartTotalProperty()
    {
        $total = 0;
        if (empty($this->cart)) return 0;

        $products = Product::whereIn('id', array_keys($this->cart))->get();
        foreach ($products as $product) {
            $total += $product->sell_price * ($this->cart[$product->id] ?? 0);
        }
        return $total;
    }

    public function getCartItemsProperty()
    {
        if (empty($this->cart)) return collect();
        
        return Product::whereIn('id', array_keys($this->cart))
            ->get()
            ->map(function ($product) {
                $product->cart_qty = $this->cart[$product->id];
                $product->subtotal = $product->sell_price * $product->cart_qty;
                return $product;
            });
    }

    // Service Tracking
    public $trackingNumber = '';
    public $trackingResult = null;

    public function trackService()
    {
        $this->validate(['trackingNumber' => 'required']);
        
        $ticket = \App\Models\ServiceTicket::where('ticket_number', $this->trackingNumber)->first();
        
        if ($ticket) {
            $this->trackingResult = $ticket;
        } else {
            $this->addError('trackingNumber', 'Nomor tiket tidak ditemukan.');
            $this->trackingResult = null;
        }
    }

    // Comparison State
    public $compareList = [];
    public $showCompareModal = false;

    public function addToCompare($productId)
    {
        if (in_array($productId, $this->compareList)) {
            $this->dispatch('notify', message: 'Produk sudah ada di perbandingan.', type: 'error');
            return;
        }

        if (count($this->compareList) >= 3) {
            $this->dispatch('notify', message: 'Maksimal 3 produk untuk dibandingkan.', type: 'error');
            return;
        }

        $this->compareList[] = $productId;
        $this->dispatch('notify', message: 'Ditambahkan ke perbandingan.');
    }

    public function removeFromCompare($productId)
    {
        $this->compareList = array_diff($this->compareList, [$productId]);
    }

    public function openCompare()
    {
        if (count($this->compareList) < 2) {
            $this->dispatch('notify', message: 'Pilih minimal 2 produk.', type: 'error');
            return;
        }
        $this->showCompareModal = true;
    }

    public function closeCompare()
    {
        $this->showCompareModal = false;
    }

    public function render()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->with(['category', 'reviews']) // Eager load reviews
            ->withAvg('reviews', 'rating')
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->whereBetween('sell_price', [$this->minPrice, $this->maxPrice])
            ->when($this->sort === 'price_asc', function ($q) {
                $q->orderBy('sell_price', 'asc');
            })
            ->when($this->sort === 'price_desc', function ($q) {
                $q->orderBy('sell_price', 'desc');
            })
            ->when($this->sort === 'latest', function ($q) {
                $q->latest();
            })
            ->paginate(12);

        $categories = Category::has('products')->get();
        $banners = Banner::where('is_active', true)->orderBy('order')->get();
        
        // Ambil Flash Sale yang sedang aktif
        $flashSales = FlashSale::with('product')
            ->where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->where('quota', '>', 0)
            ->take(4)
            ->get();

        return view('livewire.store.home', [
            'products' => $products,
            'categories' => $categories,
            'banners' => $banners,
            'flashSales' => $flashSales
        ]);
    }
}
