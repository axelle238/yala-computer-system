<?php

namespace App\Livewire\Orders;

use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Order Details')]
class Show extends Component
{
    public Order $order;

    public function mount($id)
    {
        $this->order = Order::with('items.product')->findOrFail($id);
    }

    public function updateStatus($status)
    {
        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        if (in_array($status, $validStatuses)) {
            $this->order->update(['status' => $status]);
            $this->dispatch('notify', message: "Order status updated to $status", type: 'success');
        }
    }

    public function verifyPayment()
    {
        if ($this->order->payment_status === 'paid') return;

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

        $this->dispatch('notify', message: "Pembayaran diverifikasi. Stok dikurangi.", type: 'success');
    }

    public function rejectPayment()
    {
        $this->order->update(['payment_status' => 'unpaid']);
        $this->dispatch('notify', message: "Pembayaran ditolak/di-reset.", type: 'warning');
    }

    public function updatePaymentStatus($status)
    {
        // Manual override
        $validStatuses = ['unpaid', 'paid', 'refunded', 'pending_approval'];
        if (in_array($status, $validStatuses)) {
            $this->order->update(['payment_status' => $status]);
            $this->dispatch('notify', message: "Payment status updated to $status", type: 'info');
        }
    }

    public function render()
    {
        return view('livewire.orders.show');
    }
}
