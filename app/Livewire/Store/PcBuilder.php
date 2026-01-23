<?php

namespace App\Livewire\Store;

use App\Models\Product;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Simulasi Rakit PC - Yala Computer')]
class PcBuilder extends Component
{
    // Selected Components [category_slug => product_id]
    public $selection = [
        'processors' => null,
        'motherboards' => null,
        'rams' => null,
        'gpus' => null,
        'storage' => null,
        'cases' => null,
        'psus' => null,
    ];

    public $partsList = [
        'processors' => 'Processor (CPU)',
        'motherboards' => 'Motherboard',
        'rams' => 'Memory (RAM)',
        'gpus' => 'VGA (Graphics Card)',
        'storage' => 'Storage (SSD/HDD)',
        'cases' => 'Casing PC',
        'psus' => 'Power Supply (PSU)',
    ];

    public $compatibilityIssues = [];
    public $estimatedWattage = 0;

    public function selectPart($categorySlug, $productId)
    {
        $this->selection[$categorySlug] = $productId;
        $this->checkCompatibility();
    }

    public function removePart($categorySlug)
    {
        $this->selection[$categorySlug] = null;
        $this->checkCompatibility();
    }

    public function checkCompatibility()
    {
        $this->compatibilityIssues = [];
        $this->estimatedWattage = 0;

        $cpu = $this->getProduct('processors');
        $mobo = $this->getProduct('motherboards');
        $ram = $this->getProduct('rams');
        $gpu = $this->getProduct('gpus');
        $psu = $this->getProduct('psus');

        // Wattage Calculation
        if ($cpu) $this->estimatedWattage += ($cpu->tdp_watts ?? 65);
        if ($gpu) $this->estimatedWattage += ($gpu->tdp_watts ?? 150);
        // Base system overhead
        $this->estimatedWattage += 50; 

        // Socket Check
        if ($cpu && $mobo) {
            if ($cpu->socket_type && $mobo->socket_type && $cpu->socket_type !== $mobo->socket_type) {
                $this->compatibilityIssues[] = "CPU Socket ({$cpu->socket_type}) tidak cocok dengan Motherboard ({$mobo->socket_type}).";
            }
        }

        // RAM Check
        if ($ram && $mobo) {
            if ($ram->memory_type && $mobo->memory_type && $ram->memory_type !== $mobo->memory_type) {
                $this->compatibilityIssues[] = "Tipe RAM ({$ram->memory_type}) tidak didukung oleh Motherboard ({$mobo->memory_type}).";
            }
        }

        // PSU Check
        if ($psu && $this->estimatedWattage > ($psu->wattage ?? 0)) {
            $this->compatibilityIssues[] = "PSU ({$psu->wattage}W) mungkin tidak cukup untuk sistem ini (Est: {$this->estimatedWattage}W).";
        }
    }

    protected function getProduct($slug)
    {
        $id = $this->selection[$slug] ?? null;
        return $id ? Product::find($id) : null;
    }

    public function getTotalPriceProperty()
    {
        $total = 0;
        foreach ($this->selection as $id) {
            if ($id) {
                $product = Product::find($id);
                if ($product) $total += $product->sell_price;
            }
        }
        return $total;
    }

    public function sendToWhatsapp()
    {
        $message = "Halo Yala Computer, saya mau konsultasi rakitan PC ini:\n\n";
        
        foreach ($this->partsList as $slug => $label) {
            $id = $this->selection[$slug];
            if ($id) {
                $product = Product::find($id);
                $price = number_format($product->sell_price, 0, ',', '.');
                $message .= "• *{$label}*: {$product->name}\n";
                // Add specs if available, cleaned up
                if ($product->description) {
                    $specs = strip_tags($product->description);
                    $specs = \Illuminate\Support\Str::limit($specs, 100);
                    $message .= "  _Specs: {$specs}_\n";
                }
                $message .= "  Rp {$price}\n\n";
            }
        }

        $total = number_format($this->getTotalPriceProperty(), 0, ',', '.');
        $message .= "\n💰 *Total Estimasi: Rp {$total}*";
        
        if (!empty($this->compatibilityIssues)) {
            $message .= "\n\n⚠️ *Catatan Kompatibilitas:*";
            foreach ($this->compatibilityIssues as $issue) {
                $message .= "\n- " . $issue;
            }
        }

        $message .= "\n\n📍 _Mohon info ketersediaan stok dan biaya pengiriman._";

        $waNumber = Setting::get('whatsapp_number', '6281234567890');
        $encodedMessage = urlencode($message);
        
        return redirect()->away("https://wa.me/{$waNumber}?text={$encodedMessage}");
    }

    public function render()
    {
        // Load products for each category
        $catalog = [];
        foreach (array_keys($this->partsList) as $slug) {
            $catalog[$slug] = Product::whereHas('category', function($q) use ($slug) {
                $q->where('slug', $slug);
            })->where('is_active', true)->where('stock_quantity', '>', 0)->get();
        }

        return view('livewire.store.pc-builder', [
            'catalog' => $catalog
        ]);
    }
}
