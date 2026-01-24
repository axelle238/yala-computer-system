<?php

namespace App\Livewire\Assembly;

use App\Models\Order;
use App\Models\PcAssembly;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Produksi Rakitan - Yala Computer')]
class Manager extends Component
{
    use WithPagination;

    public $activeTab = 'queued'; // queued, building, testing, completed
    
    // Modal Input
    public $showCreateModal = false;
    public $selectedOrderId;
    
    // Processing
    public $activeAssemblyId;
    public $benchmarkScore;
    public $technicianNotes;

    public function createFromOrder()
    {
        $this->validate(['selectedOrderId' => 'required']);
        
        $order = Order::with('items.product')->find($this->selectedOrderId);
        
        $specs = $order->items->map(function($item) {
            return $item->quantity . 'x ' . $item->product->name;
        })->join(", ");

        PcAssembly::create([
            'order_id' => $order->id,
            'build_name' => 'Rakitan Order #' . $order->order_number,
            'status' => 'queued',
            'specs_snapshot' => $specs
        ]);

        $this->showCreateModal = false;
        $this->dispatch('notify', message: 'Job Rakitan berhasil dibuat!', type: 'success');
    }

    public function takeJob($id)
    {
        $assembly = PcAssembly::findOrFail($id);
        $assembly->update([
            'technician_id' => Auth::id(),
            'status' => 'building',
            'started_at' => now()
        ]);
        $this->dispatch('notify', message: 'Anda memulai pengerjaan rakitan ini.', type: 'success');
    }

    public function updateStatus($id, $status)
    {
        $assembly = PcAssembly::findOrFail($id);
        $data = ['status' => $status];
        
        if ($status === 'completed') {
            $data['completed_at'] = now();
        }
        
        $assembly->update($data);
        $this->dispatch('notify', message: 'Status diperbarui: ' . strtoupper($status), type: 'success');
    }

    public function saveNotes($id)
    {
        $assembly = PcAssembly::findOrFail($id);
        $assembly->update([
            'benchmark_score' => $this->benchmarkScore,
            'technician_notes' => $this->technicianNotes
        ]);
        $this->dispatch('notify', message: 'Catatan teknis disimpan.', type: 'success');
    }

    public function loadAssemblyData($id)
    {
        $assembly = PcAssembly::findOrFail($id);
        $this->activeAssemblyId = $id;
        $this->benchmarkScore = $assembly->benchmark_score;
        $this->technicianNotes = $assembly->technician_notes;
    }

    public function render()
    {
        $assemblies = PcAssembly::with(['order.user', 'technician'])
            ->when($this->activeTab !== 'all', fn($q) => $q->where('status', $this->activeTab))
            ->latest()
            ->paginate(10);

        // Get orders eligible for assembly (simplified: completed payment, not yet assembled)
        $eligibleOrders = Order::where('payment_status', 'paid')
            ->whereDoesntHave('pcAssembly')
            ->latest()
            ->take(20)
            ->get();

        return view('livewire.assembly.manager', [
            'assemblies' => $assemblies,
            'eligibleOrders' => $eligibleOrders
        ]);
    }
}
