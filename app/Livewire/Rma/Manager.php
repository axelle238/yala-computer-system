<?php

namespace App\Livewire\Rma;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rma;
use App\Models\RmaItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Manajemen Retur & Garansi (RMA)')]
class Manager extends Component
{
    use WithPagination;

    // View State
    public $viewMode = 'list'; // list, create, process
    public $selectedRmaId;
    public $activeRma;

    // Create Form Input
    public $searchOrder = '';
    public $foundOrder = null;
    public $selectedOrderItems = []; // [order_item_id => ['selected' => bool, 'qty' => 1, 'reason' => '']]
    public $guestName = '';
    public $guestPhone = '';
    public $createNotes = '';

    // Process Input
    public $processStatus;
    public $processNote;
    public $processResolution;

    public function mount()
    {
        //
    }

    // --- Create Logic ---

    public function searchOrderAction()
    {
        $this->foundOrder = Order::with('items.product')
            ->where('order_number', 'like', '%' . $this->searchOrder . '%')
            ->first();
            
        if (!$this->foundOrder) {
            $this->addError('searchOrder', 'Order tidak ditemukan.');
        } else {
            $this->guestName = $this->foundOrder->guest_name ?? $this->foundOrder->user->name ?? '';
            $this->guestPhone = $this->foundOrder->guest_whatsapp ?? $this->foundOrder->user->phone ?? '';
        }
    }

    public function storeRma()
    {
        $this->validate([
            'foundOrder' => 'required',
            'createNotes' => 'required',
        ]);

        // Cek item yang dipilih
        $itemsToReturn = collect($this->selectedOrderItems)
            ->where('selected', true);

        if ($itemsToReturn->isEmpty()) {
            $this->addError('items', 'Pilih minimal satu barang untuk diretur.');
            return;
        }

        DB::transaction(function () use ($itemsToReturn) {
            // 1. Create Header
            $rma = Rma::create([
                'rma_number' => 'RMA-' . date('Ymd') . '-' . rand(100, 999),
                'user_id' => $this->foundOrder->user_id,
                'guest_name' => $this->guestName,
                'guest_phone' => $this->guestPhone,
                'order_id' => $this->foundOrder->id,
                'status' => 'requested',
                'notes' => $this->createNotes,
                'created_by' => Auth::id(),
            ]);

            // 2. Create Items
            foreach ($itemsToReturn as $orderItemId => $data) {
                $orderItem = OrderItem::find($orderItemId);
                if ($orderItem) {
                    RmaItem::create([
                        'rma_id' => $rma->id,
                        'product_id' => $orderItem->product_id,
                        'quantity' => $data['qty'] ?? 1,
                        'problem_description' => $data['reason'] ?? '-',
                        'condition' => 'Perlu Pengecekan',
                    ]);
                }
            }
        });

        session()->flash('success', 'RMA berhasil dibuat. Menunggu persetujuan.');
        $this->reset(['viewMode', 'foundOrder', 'searchOrder', 'selectedOrderItems']);
    }

    // --- Process Logic ---

    public function openProcess($id)
    {
        $this->selectedRmaId = $id;
        $this->activeRma = Rma::with('items.product')->find($id);
        $this->processStatus = $this->activeRma->status;
        $this->processResolution = $this->activeRma->resolution_type;
        $this->viewMode = 'process';
    }

    public function updateStatus()
    {
        if (!$this->activeRma) return;

        $this->activeRma->update([
            'status' => $this->processStatus,
            'resolution_type' => $this->processResolution,
            'admin_notes' => $this->processNote, // Map to existing column
        ]);

        session()->flash('success', 'Status RMA diperbarui.');
        $this->viewMode = 'list';
    }

    public function render()
    {
        $rmas = Rma::with(['user', 'order'])
            ->latest()
            ->paginate(10);

        return view('livewire.rma.manager', [
            'rmas' => $rmas
        ]);
    }
}
