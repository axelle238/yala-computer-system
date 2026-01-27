<?php

namespace App\Livewire\Member;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Rma;
use App\Models\RmaItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.member')]
#[Title('Ajukan Klaim Garansi - Yala Computer')]
class RmaRequest extends Component
{
    use WithFileUploads;

    // Selection State
    public $selectedOrderId = null;

    public $selectedItems = []; // [order_item_id => ['selected' => bool, 'reason' => '', 'condition' => '', 'qty' => 1]]

    // Form Data
    public $description = '';

    public $evidencePhotos = []; // Temporary upload

    public function mount()
    {
        if (! Auth::check()) {
            return redirect()->route('pelanggan.masuk');
        }
    }

    public function updatedSelectedOrderId()
    {
        $this->reset(['selectedItems', 'description', 'evidencePhotos']);
        $this->selectedItems = [];
    }

    public function submitRequest()
    {
        $this->validate([
            'selectedOrderId' => 'required|exists:orders,id',
            'description' => 'required|string|min:10',
            'selectedItems' => 'required|array',
            'evidencePhotos.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:5120', // Max 5MB
        ]);

        // Filter item yang dicentang
        $itemsToReturn = array_filter($this->selectedItems, fn ($i) => isset($i['selected']) && $i['selected']);

        if (empty($itemsToReturn)) {
            $this->dispatch('notify', message: 'Pilih minimal satu produk untuk diretur.', type: 'error');

            return;
        }

        // Proses Upload File
        $evidencePaths = [];
        if ($this->evidencePhotos) {
            foreach ($this->evidencePhotos as $photo) {
                $evidencePaths[] = $photo->store('rma-evidence', 'public');
            }
        }

        $order = Order::find($this->selectedOrderId);

        // Create RMA Header
        $rma = Rma::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'rma_number' => 'RMA-'.date('Ymd').'-'.rand(100, 999),
            'reason' => $this->description,
            'status' => Rma::STATUS_REQUESTED,
        ]);

        // Create RMA Items
        foreach ($itemsToReturn as $orderItemId => $data) {
            $orderItem = OrderItem::find($orderItemId);
            if ($orderItem) {
                RmaItem::create([
                    'rma_id' => $rma->id,
                    'product_id' => $orderItem->product_id,
                    'quantity' => $data['qty'] ?? 1,
                    'problem_description' => $data['reason'] ?? 'Tidak disebutkan',
                    'condition' => $data['condition'] ?? 'Lengkap',
                    'evidence_files' => ! empty($evidencePaths) ? json_encode($evidencePaths) : null,
                ]);
            }
        }

        session()->flash('success', 'Permintaan RMA #'.$rma->rma_number.' berhasil dikirim. Tim kami akan segera meninjau.');

        return redirect()->route('anggota.beranda');
    }

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'completed') // Hanya order selesai yang bisa RMA
            ->where('created_at', '>=', now()->subMonths(12)) // Garansi asumsi 1 tahun untuk list ini
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedOrderItems = [];
        if ($this->selectedOrderId) {
            $selectedOrderItems = OrderItem::where('order_id', $this->selectedOrderId)->with('product')->get();
        }

        // History RMA
        $rmaHistory = Rma::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->get();

        return view('livewire.member.rma-request', [
            'orders' => $orders,
            'orderItems' => $selectedOrderItems,
            'rmaHistory' => $rmaHistory,
        ]);
    }
}
