<?php

namespace App\Livewire\Logistics;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShippingManifest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Pengiriman & Logistik - Yala Computer')]
class Manager extends Component
{
    use WithPagination;

    public $activeTab = 'ready_to_ship'; // ready_to_ship, shipped, manifests
    
    // Bulk Action
    public $selectedOrders = [];
    public $bulkTrackingNumber = []; // [order_id => 'resi']
    
    // Manifest Creation
    public $showManifestModal = false;
    public $manifestCourier = 'jne';

    public function updatedActiveTab()
    {
        $this->resetPage();
        $this->selectedOrders = [];
    }

    public function shipOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        $resi = $this->bulkTrackingNumber[$orderId] ?? null;

        if (!$resi) {
            $this->dispatch('notify', message: 'Masukkan nomor resi terlebih dahulu.', type: 'error');
            return;
        }

        DB::transaction(function () use ($order, $resi) {
            // Create Shipment
            Shipment::create([
                'order_id' => $order->id,
                'courier_name' => $order->shipping_courier,
                'tracking_number' => $resi,
                'shipping_cost' => $order->shipping_cost,
                'status' => 'shipped',
                'shipped_at' => now(),
            ]);

            // Update Order Status
            $order->update(['status' => 'shipped']);
        });

        $this->dispatch('notify', message: 'Pesanan ditandai dikirim.', type: 'success');
    }

    public function createManifest()
    {
        $this->validate([
            'selectedOrders' => 'required|array|min:1',
            'manifestCourier' => 'required'
        ]);

        DB::transaction(function () {
            $manifest = ShippingManifest::create([
                'manifest_number' => 'MAN-' . date('Ymd') . '-' . rand(100, 999),
                'courier_name' => $this->manifestCourier,
                'created_by' => Auth::id(),
                'status' => 'ready_for_pickup'
            ]);

            // Link shipments (Assuming shipments already created or create them now? Let's assume created)
            // But if user selects "Ready to Ship" orders, we need to create shipments first if not exist.
            // Simplified: Only allow manifesting ALREADY SHIPPED orders (that have resi) but not yet manifested.
            
            $shipments = Shipment::whereIn('order_id', $this->selectedOrders)->get();
            foreach ($shipments as $shipment) {
                $shipment->update(['shipping_manifest_id' => $manifest->id]);
            }
        });

        $this->showManifestModal = false;
        $this->selectedOrders = [];
        $this->activeTab = 'manifests';
        $this->dispatch('notify', message: 'Manifest berhasil dibuat.', type: 'success');
    }

    public function render()
    {
        $orders = [];
        $manifests = [];

        if ($this->activeTab === 'ready_to_ship') {
            $orders = Order::where('status', 'processing') // Or 'paid' if logic differs
                ->orWhere('status', 'paid') // Usually paid orders are ready to process/ship
                ->latest()
                ->paginate(10);
        } elseif ($this->activeTab === 'shipped') {
            $orders = Order::where('status', 'shipped')
                ->whereDoesntHave('shipment.manifest') // Not yet manifested
                ->latest()
                ->paginate(10);
        } elseif ($this->activeTab === 'manifests') {
            $manifests = ShippingManifest::withCount('shipments')->latest()->paginate(10);
        }

        return view('livewire.logistics.manager', [
            'orders' => $orders,
            'manifests' => $manifests
        ]);
    }
}
