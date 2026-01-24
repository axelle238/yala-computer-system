<?php

namespace App\Livewire\Store;

use App\Models\Product;
use App\Models\SavedBuild;
use App\Models\Setting;
use App\Services\PcCompatibilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Simulasi Rakit PC Professional - Yala Computer')]
class PcBuilder extends Component
{
    use WithPagination;

    // Build State
    public $buildName = 'My Custom PC';
    public $selection = []; 
    public $totalPrice = 0;
    public $estimatedWattage = 0;
    
    // Status Messages
    public $compatibilityIssues = [];
    public $compatibilityWarnings = [];
    public $compatibilityInfo = [];

    // Modal Selector State
    public $showSelector = false;
    public $currentCategory = null;
    public $searchQuery = '';
    
    // Config
    public $partsList = [
        'processors' => ['label' => 'Processor (CPU)', 'icon' => 'cpu'],
        'motherboards' => ['label' => 'Motherboard', 'icon' => 'server'],
        'rams' => ['label' => 'Memory (RAM)', 'icon' => 'memory'],
        'gpus' => ['label' => 'Graphic Card (VGA)', 'icon' => 'monitor'],
        'storage' => ['label' => 'Storage (SSD/NVMe)', 'icon' => 'hard-drive'],
        'cases' => ['label' => 'Casing PC', 'icon' => 'box'],
        'psus' => ['label' => 'Power Supply (PSU)', 'icon' => 'zap'],
        'coolers' => ['label' => 'CPU Cooler', 'icon' => 'wind'],
    ];

    public function mount()
    {
        // Initialize empty selection
        foreach (array_keys($this->partsList) as $key) {
            $this->selection[$key] = null;
        }

        // Load Cloned Build
        if (session()->has('cloned_build')) {
            $this->selection = session()->get('cloned_build');
            $this->buildName = session()->get('cloned_build_name', 'My New Custom PC');
            $this->recalculate();
            
            // Clear session (Flash data behavior)
            session()->forget(['cloned_build', 'cloned_build_name']);
        }
    }

    // --- Modal Selection Logic ---

    public function openSelector($category)
    {
        $this->currentCategory = $category;
        $this->searchQuery = '';
        $this->showSelector = true;
        $this->resetPage(); 
    }

    public function closeSelector()
    {
        $this->showSelector = false;
        $this->currentCategory = null;
    }

    public function selectProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->selection[$this->currentCategory] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->sell_price,
                'image' => $product->image_path,
                'specs' => $product->specifications // Store raw specs for service
            ];
            $this->recalculate();
        }
        $this->closeSelector();
    }

    public function removePart($category)
    {
        $this->selection[$category] = null;
        $this->recalculate();
    }

    // --- Calculation & Logic ---

    public function recalculate()
    {
        $this->totalPrice = 0;

        // 1. Sum Price
        foreach ($this->selection as $item) {
            if ($item) {
                $this->totalPrice += $item['price'];
            }
        }

        // 2. Compatibility Service
        $service = new PcCompatibilityService();
        $result = $service->checkCompatibility($this->selection);

        $this->compatibilityIssues = $result['issues'];
        $this->compatibilityWarnings = $result['warnings'];
        $this->compatibilityInfo = $result['info'];
        $this->estimatedWattage = $result['wattage'];
    }

    // --- Actions ---

    public function saveBuild()
    {
        if (!Auth::check()) {
            $this->dispatch('notify', message: 'Silakan login untuk menyimpan rakitan.', type: 'error');
            return redirect()->route('login');
        }

        SavedBuild::create([
            'user_id' => Auth::id(),
            'name' => $this->buildName,
            'description' => 'Disimpan pada ' . now()->format('d M Y'),
            'total_price_estimated' => $this->totalPrice,
            'components' => $this->selection,
            'share_token' => Str::random(32),
        ]);

        $this->dispatch('notify', message: 'Rakitan berhasil disimpan di profil Anda!', type: 'success');
    }

    public function addToCart()
    {
        $cart = session()->get('cart', []);
        $count = 0;

        foreach ($this->selection as $item) {
            if ($item) {
                $id = $item['id'];
                if (isset($cart[$id])) {
                    $cart[$id]['quantity']++;
                } else {
                    $cart[$id] = [
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'quantity' => 1,
                        'image' => $item['image']
                    ];
                }
                $count++;
            }
        }

        if ($count > 0) {
            session()->put('cart', $cart);
            $this->dispatch('cart-updated'); // Event for Cart Badge
            return redirect()->route('cart');
        } else {
             $this->dispatch('notify', message: 'Pilih komponen terlebih dahulu.', type: 'warning');
        }
    }

    public function printPdf()
    {
        // Placeholder for PDF generation
        $this->dispatch('notify', message: 'Fitur cetak PDF akan segera hadir!', type: 'info');
    }

    public function render()
    {
        $products = [];
        if ($this->showSelector && $this->currentCategory) {
            $products = Product::where('is_active', true)
                ->whereHas('category', function($q) {
                    $q->where('slug', $this->currentCategory);
                })
                ->where('name', 'like', '%' . $this->searchQuery . '%')
                ->orderBy('sell_price')
                ->paginate(10);
        }

        return view('livewire.store.pc-builder', [
            'products' => $products
        ]);
    }
}