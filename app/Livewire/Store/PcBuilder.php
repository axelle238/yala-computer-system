<?php

namespace App\Livewire\Store;

use App\Models\Category;
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

    public function selectPart($categorySlug, $productId)
    {
        $this->selection[$categorySlug] = $productId;
    }

    public function removePart($categorySlug)
    {
        $this->selection[$categorySlug] = null;
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
        $message .= "\n\nMohon dicek ketersediaan dan kompatibilitasnya. Terima kasih!";

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