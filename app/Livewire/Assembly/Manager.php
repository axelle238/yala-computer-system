<?php

namespace App\Livewire\Assembly;

use App\Models\PcAssembly;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('PC Assembly Manager')]
class Manager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    // Modal State
    public $showDetail = false;
    public $selectedAssembly = null;
    
    // Technician Input
    public $technicianNotes = '';
    public $benchmarkScore = '';
    public $specs = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openDetail($id)
    {
        $this->selectedAssembly = PcAssembly::with(['order.user', 'order.items.product'])->find($id);
        
        if ($this->selectedAssembly) {
            $this->technicianNotes = $this->selectedAssembly->technician_notes;
            $this->benchmarkScore = $this->selectedAssembly->benchmark_score;
            
            // Parse Specs from JSON or rebuild from Order Items if empty
            if ($this->selectedAssembly->specs_snapshot) {
                $this->specs = json_decode($this->selectedAssembly->specs_snapshot, true);
            } else {
                // Fallback: Try to guess from order items (Not ideal but helpful)
                $this->specs = $this->selectedAssembly->order->items->map(function($item) {
                    return [
                        'name' => $item->product->name,
                        'qty' => $item->quantity
                    ];
                })->toArray();
            }

            $this->showDetail = true;
        }
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedAssembly = null;
    }

    public function updateStatus($newStatus)
    {
        if (!$this->selectedAssembly) return;

        $this->selectedAssembly->update([
            'status' => $newStatus,
            'technician_id' => auth()->id(), // Assign current user as technician
        ]);

        if ($newStatus === 'picking' && !$this->selectedAssembly->started_at) {
            $this->selectedAssembly->update(['started_at' => now()]);
        }
        
        if ($newStatus === 'completed') {
            $this->selectedAssembly->update(['completed_at' => now()]);
        }

        $this->dispatch('notify', message: 'Status updated to ' . ucfirst($newStatus), type: 'success');
    }

    public function saveNotes()
    {
        if (!$this->selectedAssembly) return;

        $this->selectedAssembly->update([
            'technician_notes' => $this->technicianNotes,
            'benchmark_score' => $this->benchmarkScore,
        ]);

        $this->dispatch('notify', message: 'Notes & Benchmark saved.', type: 'success');
    }

    public function render()
    {
        $assemblies = PcAssembly::query()
            ->with(['order', 'technician'])
            ->when($this->search, function($q) {
                $q->whereHas('order', function($sq) {
                    $sq->where('order_number', 'like', '%'.$this->search.'%')
                       ->orWhere('guest_name', 'like', '%'.$this->search.'%');
                })->orWhere('build_name', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.assembly.manager', [
            'assemblies' => $assemblies
        ]);
    }
}