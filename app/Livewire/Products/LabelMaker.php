<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Cetak Label & Barcode - Yala Computer')]
class LabelMaker extends Component
{
    public $search = '';
    public $queue = []; // [product_id => qty]
    
    // Label Settings
    public $labelType = 'price_tag'; // price_tag, barcode_sticker, qr_mini
    public $paperSize = 'a4'; // a4, thermal_100x150

    public function mount()
    {
        $this->queue = Session::get('label_print_queue', []);
    }

    public function addToQueue($id)
    {
        if (isset($this->queue[$id])) {
            $this->queue[$id]++;
        } else {
            $this->queue[$id] = 1;
        }
        Session::put('label_print_queue', $this->queue);
        $this->dispatch('notify', message: 'Ditambahkan ke antrian cetak.', type: 'success');
    }

    public function removeFromQueue($id)
    {
        unset($this->queue[$id]);
        Session::put('label_print_queue', $this->queue);
    }

    public function updateQty($id, $qty)
    {
        if ($qty > 0) {
            $this->queue[$id] = $qty;
        } else {
            unset($this->queue[$id]);
        }
        Session::put('label_print_queue', $this->queue);
    }

    public function clearQueue()
    {
        $this->queue = [];
        Session::forget('label_print_queue');
        $this->dispatch('notify', message: 'Antrian dibersihkan.', type: 'success');
    }

    public function print()
    {
        if (empty($this->queue)) {
            $this->dispatch('notify', message: 'Antrian kosong!', type: 'error');
            return;
        }

        // Redirect to print controller logic (stream PDF/HTML)
        return redirect()->route('print.labels', [
            'type' => $this->labelType,
            'paper' => $this->paperSize
        ]);
    }

    public function render()
    {
        $products = [];
        if (strlen($this->search) > 2) {
            $products = Product::where('name', 'like', '%'.$this->search.'%')
                ->orWhere('sku', 'like', '%'.$this->search.'%')
                ->take(10)->get();
        }

        $queueItems = [];
        if (!empty($this->queue)) {
            $queueItems = Product::whereIn('id', array_keys($this->queue))->get();
        }

        return view('livewire.products.label-maker', [
            'products' => $products,
            'queueItems' => $queueItems
        ]);
    }
}