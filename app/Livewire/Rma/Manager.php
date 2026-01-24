<?php

namespace App\Livewire\Rma;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\Rma;
use App\Models\RmaItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('RMA & Garansi Center - Yala Computer')]
class Manager extends Component
{
    use WithPagination;

    public $activeTab = 'pending'; // pending, processing, completed
    
    // Process Modal
    public $selectedRma = null;
    public $showProcessModal = false;
    
    // Action Inputs
    public $actionType = 'replacement'; // replacement, refund, reject
    public $adminNotes;
    public $refundAmount = 0;
    public $replacementItems = []; // [item_id => ['sn' => '...']]

    // SN Lookup (Create New)
    public $showCreateModal = false;
    public $lookupSn = '';
    public $lookupResult = null;
    public $newRmaReason;
    public $newRmaContact;

    public function updatedActiveTab()
    {
        $this->resetPage();
    }

    // --- Create Flow ---
    public function lookupWarranty()
    {
        $serial = ProductSerial::with(['product', 'order'])->where('serial_number', $this->lookupSn)->first();
        
        if ($serial) {
            $this->lookupResult = $serial;
        } else {
            $this->addError('lookupSn', 'Serial Number tidak ditemukan.');
            $this->lookupResult = null;
        }
    }

    public function createRma()
    {
        if (!$this->lookupResult) return;

        DB::transaction(function () {
            $rma = Rma::create([
                'rma_number' => 'RMA-' . date('ymd') . '-' . rand(100, 999),
                'order_id' => $this->lookupResult->order_id,
                'guest_name' => $this->newRmaContact,
                'status' => 'pending',
                'reason' => $this->newRmaReason,
            ]);

            RmaItem::create([
                'rma_id' => $rma->id,
                'product_id' => $this->lookupResult->product_id,
                'serial_number' => $this->lookupSn,
                'problem_description' => $this->newRmaReason
            ]);
            
            // Mark SN as RMA
            $this->lookupResult->update(['status' => 'rma_customer']);
        });

        $this->showCreateModal = false;
        $this->reset(['lookupSn', 'lookupResult', 'newRmaReason', 'newRmaContact']);
        $this->dispatch('notify', message: 'Klaim Garansi Berhasil Dibuat!', type: 'success');
    }

    // --- Process Flow ---
    public function openProcess($id)
    {
        $this->selectedRma = Rma::with(['items.product', 'order'])->findOrFail($id);
        $this->showProcessModal = true;
        $this->actionType = $this->selectedRma->resolution_type ?? 'replacement';
    }

    public function processRma()
    {
        if (!$this->selectedRma) return;

        DB::transaction(function () {
            // Update Header
            $this->selectedRma->update([
                'status' => ($this->actionType === 'reject') ? 'rejected' : 'completed',
                'resolution_type' => $this->actionType,
                'admin_notes' => $this->adminNotes,
                'refund_amount' => ($this->actionType === 'refund') ? $this->refundAmount : 0,
                'stock_adjusted' => true // Prevent double processing
            ]);

            // Handle Logic based on Action
            foreach ($this->selectedRma->items as $item) {
                if ($this->actionType === 'replacement') {
                    // 1. Stock Out New Item
                    $newSn = $this->replacementItems[$item->id]['sn'] ?? null;
                    
                    if ($newSn) {
                        // Find new serial
                        $serialRecord = ProductSerial::where('serial_number', $newSn)->first();
                        if ($serialRecord) {
                            $serialRecord->update(['status' => 'sold', 'order_id' => $this->selectedRma->order_id]);
                        }

                        // Update Stock
                        $item->product->decrement('stock_quantity', 1);
                        
                        InventoryTransaction::create([
                            'product_id' => $item->product_id,
                            'user_id' => Auth::id(),
                            'type' => 'out',
                            'quantity' => 1,
                            'remaining_stock' => $item->product->stock_quantity,
                            'reference_number' => $this->selectedRma->rma_number,
                            'notes' => 'RMA Replacement for ' . $item->serial_number
                        ]);

                        // Update Item Record
                        $item->update([
                            'replacement_serial_number' => $newSn
                        ]);
                    }
                } 
                elseif ($this->actionType === 'refund') {
                    // Refund logic typically involves accounting module interaction
                    // Here we just record it.
                }
            }
        });

        $this->showProcessModal = false;
        $this->dispatch('notify', message: 'RMA Berhasil Diproses & Stok Disesuaikan.', type: 'success');
    }

    public function render()
    {
        $rmas = Rma::with(['items.product', 'user'])
            ->when($this->activeTab === 'pending', fn($q) => $q->whereIn('status', ['pending', 'received_goods']))
            ->when($this->activeTab === 'processing', fn($q) => $q->whereIn('status', ['checking', 'sent_to_distributor']))
            ->when($this->activeTab === 'completed', fn($q) => $q->whereIn('status', ['completed', 'rejected']))
            ->latest()
            ->paginate(10);

        return view('livewire.rma.manager', [
            'rmas' => $rmas
        ]);
    }
}
