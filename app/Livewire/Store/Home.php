<?php

namespace App\Livewire\Store;

use App\Models\Article;
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
use Livewire\Attributes\Computed;

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
    public $maxPrice = 50000000;

    #[Url(history: true)]
    public $sort = 'latest';

    // Cart State
    public $isCartOpen = false;
    public $cart = [];

    // Feature Toggles
    public $flashSaleEnabled = true;
    public $serviceTrackingEnabled = true;

    // Comparison
    public $compareList = [];
    public $showCompareModal = false;

    public function mount()
    {
        $this->cart = Session::get('cart', []);
        $this->compareList = Session::get('compareList', []);
        $this->flashSaleEnabled = (bool) Setting::get('feature_flash_sale', true);
        $this->serviceTrackingEnabled = (bool) Setting::get('feature_service_tracking', true);
    }

    // ... (Keep existing methods: updatedSearch, updatedCategory, openProduct, toggleCart, addToCart, removeFromCart, updateCartQty, checkout, trackService, comparison methods) ...
    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }
    public function openProduct($id) { return redirect()->route('product.detail', $id); }
    public function toggleCart() { $this->isCartOpen = !$this->isCartOpen; }
    public function addToCart($productId, $qty = 1) {
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
        $this->isCartOpen = true;
    }
    public function removeFromCart($productId) { unset($this->cart[$productId]); Session::put('cart', $this->cart); }
    public function updateCartQty($productId, $change) {
        if (!isset($this->cart[$productId])) return;
        $newQty = $this->cart[$productId] + $change;
        $product = Product::find($productId);
        if ($newQty > 0 && $newQty <= $product->stock_quantity) {
            $this->cart[$productId] = $newQty;
            Session::put('cart', $this->cart);
        } elseif ($newQty <= 0) {
            $this->removeFromCart($productId);
        }
    }
    public function checkout() {
        if (empty($this->cart)) return;
        $message = "*Halo Yala Computer, saya ingin memesan:*\n\n";
        $total = 0;
        $products = Product::whereIn('id', array_keys($this->cart))->get();
        foreach ($products as $product) {
            $qty = $this->cart[$product->id];
            $subtotal = $product->sell_price * $qty;
            $total += $subtotal;
            $message .= "📦 *{$product->name}*\n   └ x{$qty} @ Rp " . number_format($product->sell_price, 0, ',', '.') . " = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        }
        $message .= "\n💰 *Total Estimasi: Rp " . number_format($total, 0, ',', '.') . "*\n\n📍 _Mohon info ketersediaan stok dan biaya pengiriman._";
        return redirect()->away("https://wa.me/" . Setting::get('whatsapp_number', '6281234567890') . "?text=" . urlencode($message));
    }
    
    // Service Tracking
    public $trackingNumber = '';
    public $trackingResult = null;
    public function trackService() {
        $this->validate(['trackingNumber' => 'required']);
        $ticket = \App\Models\ServiceTicket::where('ticket_number', $this->trackingNumber)->first();
        if ($ticket) { $this->trackingResult = $ticket; } else { $this->addError('trackingNumber', 'Nomor tiket tidak ditemukan.'); $this->trackingResult = null; }
    }

    // Comparison
    public function addToCompare($productId) {
        if (in_array($productId, $this->compareList)) { $this->dispatch('notify', message: 'Produk sudah ada di perbandingan.', type: 'error'); return; }
        if (count($this->compareList) >= 3) { $this->dispatch('notify', message: 'Maksimal 3 produk.', type: 'error'); return; }
        $this->compareList[] = $productId;
        Session::put('compareList', $this->compareList); // Persist
        $this->dispatch('notify', message: 'Ditambahkan ke perbandingan.');
    }
    public function removeFromCompare($productId) {
        $this->compareList = array_diff($this->compareList, [$productId]);
        Session::put('compareList', $this->compareList);
    }
    public function openCompare() {
        if (count($this->compareList) < 2) { $this->dispatch('notify', message: 'Pilih minimal 2 produk.', type: 'error'); return; }
        $this->showCompareModal = true;
    }
    public function closeCompare() { $this->showCompareModal = false; }

    #[Computed]
    public function getCartTotalProperty() {
        $total = 0;
        if (empty($this->cart)) return 0;
        $products = Product::whereIn('id', array_keys($this->cart))->get();
        foreach ($products as $product) { $total += $product->sell_price * ($this->cart[$product->id] ?? 0); }
        return $total;
    }

    #[Computed]
    public function getCartItemsProperty() {
        if (empty($this->cart)) return collect();
        return Product::whereIn('id', array_keys($this->cart))->get()->map(function ($product) {
            $product->cart_qty = $this->cart[$product->id];
            $product->subtotal = $product->sell_price * $product->cart_qty;
            return $product;
        });
    }

    #[Computed]
    public function getCompareProductsProperty() {
        return Product::with('category')->whereIn('id', $this->compareList)->get();
    }

    public function render()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->with(['category'])
            ->withAvg('reviews', 'rating')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->category, fn($q) => $q->where('category_id', $this->category))
            ->whereBetween('sell_price', [$this->minPrice, $this->maxPrice])
            ->when($this->sort === 'price_asc', fn($q) => $q->orderBy('sell_price', 'asc'))
            ->when($this->sort === 'price_desc', fn($q) => $q->orderBy('sell_price', 'desc'))
            ->when($this->sort === 'latest', fn($q) => $q->latest())
            ->paginate(12);

        $latestNews = Article::where('is_published', true)->latest()->take(3)->get(); // Fetch News

        return view('livewire.store.home', [
            'products' => $products,
            'categories' => Category::has('products')->get(),
            'banners' => Banner::where('is_active', true)->orderBy('order')->get(),
            'flashSales' => FlashSale::with('product')->where('is_active', true)->where('start_time', '<=', now())->where('end_time', '>=', now())->take(4)->get(),
            'latestNews' => $latestNews
        ]);
    }
}