<?php

namespace App\Livewire\Logistics;

use App\Models\Order;
use App\Models\ShippingManifest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Logistics & Shipping Manifest - Yala Computer')]
class Manager extends Component
{
    use WithPagination;

    public $viewMode = 'list'; // list, create
    public $courierFilter = 'jne'; // Default
    public $selectedOrders = [];
    
    public function toggleMode($mode) { $this->viewMode = $mode; }

    public function updatedCourierFilter() { $this->selectedOrders = []; }

    public function createManifest()
    {
        if (empty($this->selectedOrders)) {
            $this->dispatch('notify', message: 'Pilih pesanan terlebih dahulu.', type: 'error');
            return;
        }

        DB::transaction(function () {
            $manifest = ShippingManifest::create([
                'manifest_number' => 'MNF-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'courier_name' => $this->courierFilter,
                'created_by' => Auth::id(),
                'status' => 'generated',
            ]);

            Order::whereIn('id', $this->selectedOrders)->update([
                'shipping_manifest_id' => $manifest->id,
                'status' => 'shipped' // Update status pesanan jadi dikirim
            ]);
        });

        $this->dispatch('notify', message: 'Manifest berhasil dibuat!', type: 'success');
        $this->selectedOrders = [];
        $this->toggleMode('list');
    }

    public function printManifest($id)
    {
        // Placeholder for printing
        $this->dispatch('notify', message: 'Mencetak Manifest ID: ' . $id, type: 'info');
    }

    public function render()
    {
        $manifests = [];
        $pendingOrders = [];

        if ($this->viewMode === 'list') {
            $manifests = ShippingManifest::withCount('orders')
                ->latest()
                ->paginate(10);
        } else {
            $pendingOrders = Order::where('status', 'processing') // Ready to ship
                ->where('shipping_courier', 'like', '%' . $this->courierFilter . '%')
                ->whereNull('shipping_manifest_id')
                ->get();
        }

        return view('livewire.logistics.manager', [
            'manifests' => $manifests,
            'pendingOrders' => $pendingOrders
        ]);
    }
}