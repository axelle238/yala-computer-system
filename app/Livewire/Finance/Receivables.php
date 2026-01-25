<?php

namespace App\Livewire\Finance;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Piutang Penjualan (Receivables) - Yala Computer')]
class Receivables extends Component
{
    use WithPagination;

    public $search = '';

    // View State
    public $activeAction = null; // null, 'payment'

    public $selectedOrderId;

    public $paymentAmount;

    public $paymentMethod = 'transfer';

    public $paymentNote;

    public function openPaymentPanel($orderId)
    {
        $this->resetValidation();
        $this->selectedOrderId = $orderId;
        $order = Order::find($orderId);
        $this->paymentAmount = $order->remaining_balance; // Default to full remaining
        $this->activeAction = 'payment';
    }

    public function closePaymentPanel()
    {
        $this->reset(['activeAction', 'selectedOrderId', 'paymentAmount', 'paymentNote']);
        $this->resetValidation();
    }

    public function savePayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:1',
            'paymentMethod' => 'required',
        ]);

        $order = Order::findOrFail($this->selectedOrderId);

        if ($this->paymentAmount > $order->remaining_balance) {
            $this->addError('paymentAmount', 'Jumlah pembayaran melebihi sisa tagihan.');

            return;
        }

        DB::transaction(function () use ($order) {
            Payment::create([
                'payment_number' => 'PAY-AR-'.date('YmdHis'),
                'payable_type' => Order::class,
                'payable_id' => $order->id,
                'amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'payment_date' => now(),
                'notes' => $this->paymentNote,
                'created_by' => Auth::id(),
            ]);

            // Update Order Payment Status
            $newPaid = $order->paid_amount + $this->paymentAmount; // Recalculate including new
            if ($newPaid >= $order->total_amount) {
                $order->update(['payment_status' => 'paid']);
            } else {
                $order->update(['payment_status' => 'partial']);
            }
        });

        $this->closePaymentPanel();
        $this->dispatch('notify', message: 'Pembayaran berhasil dicatat.', type: 'success');
    }

    public function render()
    {
        // Get orders that are NOT fully paid (unpaid or partial)
        // AND status is confirmed (not cancelled)
        $invoices = Order::with('user')
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->whereNotIn('status', ['cancelled', 'pending']) // Pending orders usually not receivables yet until confirmed
            ->when($this->search, function ($q) {
                $q->where('order_number', 'like', '%'.$this->search.'%')
                    ->orWhere('guest_name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        $totalReceivables = Order::whereIn('payment_status', ['unpaid', 'partial'])
            ->whereNotIn('status', ['cancelled', 'pending'])
            ->get()
            ->sum(function ($order) {
                return $order->remaining_balance;
            });

        return view('livewire.finance.receivables', [
            'invoices' => $invoices,
            'totalReceivables' => $totalReceivables,
        ]);
    }
}
