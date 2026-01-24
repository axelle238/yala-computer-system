<?php

namespace App\Livewire\Member;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Rma;
use App\Models\RmaItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Klaim Garansi (RMA) - Member Area')]
class RmaRequest extends Component
{
    use WithFileUploads;

    // Step Control
    public $step = 1; // 1: Select Order, 2: Select Items & Details, 3: Confirmation

    // Data
    public $selectedOrderId;
    public $selectedOrder;
    public $rmaItems = []; // ['order_item_id' => ['qty' => 1, 'condition' => '', 'reason' => '']]

    // Form
    public $resolutionPreference = 'repair'; // repair, replacement, refund
    public $generalNotes = '';
    
    // Validation Rules
    protected $rules = [
        'rmaItems' => 'required|array|min:1',
        'rmaItems.*.qty' => 'required|integer|min:1',
        'rmaItems.*.condition' => 'required|string',
        'rmaItems.*.reason' => 'required|string|min:10',
        'resolutionPreference' => 'required|in:repair,replacement,refund',
    ];

    public function mount()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrderId = $orderId;
        $this->selectedOrder = Order::with('items.product')->where('user_id', Auth::id())->findOrFail($orderId);
        
        // Cek apakah order eligible (misal: status completed)
        if ($this->selectedOrder->status !== 'completed') {
            $this->dispatch('notify', message: 'Hanya pesanan dengan status Selesai yang dapat diklaim garansi.', type: 'error');
            $this->resetSelection();
            return;
        }

        $this->step = 2;
    }

    public function resetSelection()
    {
        $this->selectedOrderId = null;
        $this->selectedOrder = null;
        $this->rmaItems = [];
        $this->step = 1;
    }

    public function toggleItem($orderItemId)
    {
        if (isset($this->rmaItems[$orderItemId])) {
            unset($this->rmaItems[$orderItemId]);
        } else {
            // Find max qty
            $item = $this->selectedOrder->items->find($orderItemId);
            
            $this->rmaItems[$orderItemId] = [
                'product_id' => $item->product_id,
                'qty' => 1,
                'max_qty' => $item->quantity, // Limit to bought qty
                'condition' => 'used',
                'reason' => ''
            ];
        }
    }

    public function submitRma()
    {
        $this->validate();

        DB::transaction(function () {
            // 1. Create RMA Header
            $rma = Rma::create([
                'rma_number' => 'RMA-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => Auth::id(),
                'order_id' => $this->selectedOrderId,
                'guest_name' => Auth::user()->name,
                'guest_phone' => Auth::user()->phone ?? '-',
                'status' => 'pending',
                'resolution_type' => $this->resolutionPreference,
                'reason' => $this->generalNotes, // General notes for the whole RMA
            ]);

            // 2. Create RMA Items
            foreach ($this->rmaItems as $orderItemId => $data) {
                RmaItem::create([
                    'rma_id' => $rma->id,
                    'product_id' => $data['product_id'],
                    'quantity' => $data['qty'],
                    'condition' => $data['condition'],
                    'problem_description' => $data['reason'],
                    // Serial number logic can be added here if we track serials in OrderItem
                ]);
            }
        });

        session()->flash('success', 'Permintaan RMA berhasil dikirim. Tim kami akan segera meninjau klaim Anda.');
        return redirect()->route('member.dashboard');
    }

    public function render()
    {
        $orders = [];
        if ($this->step === 1) {
            $orders = Order::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->latest()
                ->get();
        }

        return view('livewire.member.rma-request', [
            'orders' => $orders
        ]);
    }
}