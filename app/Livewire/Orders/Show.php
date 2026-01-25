<?php

namespace App\Livewire\Orders;

use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Order Details')]
class Show extends Component
{
    public Order $order;

    public $tracking_number;

    public function mount($id)
    {
        $this->order = Order::with('items.product')->findOrFail($id);
        $this->tracking_number = $this->order->shipping_tracking_number;
    }

    public function updateStatus($status)
    {
        $validStatuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        if (in_array($status, $validStatuses)) {
            // Logic for transitions
            if ($status === 'processing' && $this->order->status === 'pending') {
                // Auto verify payment if moving to processing? Or just move status.
                // Let's assume manual move doesn't auto-pay unless verified button used.
            }

            $this->order->update(['status' => $status]);
            $this->dispatch('notify', message: "Status pesanan diperbarui menjadi $status", type: 'success');
        }
    }

    public function updateTracking()
    {
        $this->validate(['tracking_number' => 'required|string|min:5']);
        $this->order->update(['shipping_tracking_number' => $this->tracking_number]);
        $this->dispatch('notify', message: 'Nomor resi disimpan.', type: 'success');
    }

    public function markAsShipped()
    {
        if (empty($this->tracking_number)) {
            $this->addError('tracking_number', 'Resi wajib diisi sebelum kirim.');

            return;
        }

        $this->order->update([
            'status' => 'shipped',
            'shipping_tracking_number' => $this->tracking_number,
        ]);

        $this->dispatch('notify', message: 'Order dikirim! Status: Shipped', type: 'success');
    }

    public function printLabel()
    {
        return redirect()->route('print.label', $this->order->id);
    }

    public function verifyPayment()
    {
        if ($this->order->payment_status === 'paid') {
            return;
        }

        DB::transaction(function () {
            $this->order->update([
                'status' => 'processing',
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            // Deduct Stock for Online Orders (if not already deducted)
            // POS orders deduct immediately. Online orders usually deduct upon confirmation to prevent fake orders blocking stock.
            foreach ($this->order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    $product->decrement('stock_quantity', $item->quantity);

                    InventoryTransaction::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => 'out',
                        'quantity' => $item->quantity,
                        'remaining_stock' => $product->stock_quantity,
                        'unit_price' => $item->price,
                        'cogs' => $product->buy_price,
                        'reference_number' => $this->order->order_number,
                        'notes' => 'Online Order Fulfillment',
                    ]);
                }
            }
        });

        $this->dispatch('notify', message: 'Pembayaran diverifikasi. Stok dikurangi.', type: 'success');
    }

    public function rejectPayment()
    {
        $this->order->update(['payment_status' => 'unpaid']);
        $this->dispatch('notify', message: 'Pembayaran ditolak/di-reset.', type: 'warning');
    }

    public function updatePaymentStatus($status)
    {
        // Manual override
        $validStatuses = ['unpaid', 'paid', 'refunded', 'pending_approval'];
        if (in_array($status, $validStatuses)) {
            $this->order->update(['payment_status' => $status]);
            $this->dispatch('notify', message: "Status pembayaran diperbarui menjadi $status", type: 'info');
        }
    }

    public function render()
    {
        return view('livewire.orders.show');
    }
}
