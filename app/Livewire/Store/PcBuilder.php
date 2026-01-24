<?php

namespace App\Livewire\Store;

use App\Models\Product;
use App\Models\SavedBuild;
use App\Models\Setting;
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
    public $selection = []; // [category_slug => ['product_id', 'price', 'name', 'image']]
    public $totalPrice = 0;
    public $estimatedWattage = 0;
    public $compatibilityIssues = [];

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

        // Load saved build if requested (logic could be added here via query param)
    }

    // --- Modal Selection Logic ---

    public function openSelector($category)
    {
        $this->currentCategory = $category;
        $this->searchQuery = '';
        $this->showSelector = true;
        $this->resetPage(); // Reset pagination for the new category
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
                'specs' => [
                    'socket' => $product->socket_type, // Assuming these columns exist or we parse desc
                    'wattage' => $product->tdp_watts ?? 0,
                    'memory_type' => $product->memory_type
                ]
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
        $this->estimatedWattage = 50; // Base overhead
        $this->compatibilityIssues = [];

        // Sum Price & Wattage
        foreach ($this->selection as $key => $item) {
            if ($item) {
                $this->totalPrice += $item['price'];
                
                // Wattage (CPU & GPU major contributors)
                if (in_array($key, ['processors', 'gpus'])) {
                    $this->estimatedWattage += ($item['specs']['wattage'] ?? 0);
                }
            }
        }

        // --- Compatibility Checks ---
        $cpu = $this->selection['processors'] ?? null;
        $mobo = $this->selection['motherboards'] ?? null;
        $ram = $this->selection['rams'] ?? null;
        $psu = $this->selection['psus'] ?? null; // Assume we fetch PSU wattage from simulation if not in DB column

        // 1. Socket Check
        if ($cpu && $mobo) {
            // Simulation logic: In real app, check DB columns 'socket_type'
            // Here we assume standard naming if columns specific aren't guaranteed populated
            if (isset($cpu['specs']['socket']) && isset($mobo['specs']['socket'])) {
                if ($cpu['specs']['socket'] != $mobo['specs']['socket']) {
                    $this->compatibilityIssues[] = "Socket CPU ({$cpu['specs']['socket']}) mungkin tidak cocok dengan Motherboard.";
                }
            }
        }

        // 2. RAM Generation Check
        if ($ram && $mobo) {
            // e.g. DDR4 vs DDR5
            if (str_contains(strtolower($ram['name']), 'ddr4') && str_contains(strtolower($mobo['name']), 'ddr5')) {
                $this->compatibilityIssues[] = "RAM DDR4 tidak kompatibel dengan Motherboard DDR5.";
            }
             if (str_contains(strtolower($ram['name']), 'ddr5') && str_contains(strtolower($mobo['name']), 'ddr4')) {
                $this->compatibilityIssues[] = "RAM DDR5 tidak kompatibel dengan Motherboard DDR4.";
            }
        }

        // 3. PSU Check (Simple)
        // If we had PSU wattage in DB, we'd check it. 
        // For now, simple logic: if High-End GPU + CPU > 500W and no PSU selected
        if ($this->estimatedWattage > 400 && !$psu) {
            $this->compatibilityIssues[] = "Estimasi daya tinggi ({$this->estimatedWattage}W). Jangan lupa pilih PSU yang memadai (500W+).";
        }
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