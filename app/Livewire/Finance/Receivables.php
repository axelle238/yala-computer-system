<?php

namespace App\Livewire\Finance;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Piutang Penjualan (Receivables) - Yala Computer')]
class Receivables extends Component
{
    use WithPagination;

    public $search = '';
    
    // Payment Modal
    public $showPaymentModal = false;
    public $selectedOrderId;
    public $paymentAmount;
    public $paymentMethod = 'transfer';
    public $paymentNote;

    public function openPaymentModal($orderId)
    {
        $this->selectedOrderId = $orderId;
        $order = Order::find($orderId);
        $this->paymentAmount = $order->remaining_balance; // Default to full remaining
        $this->showPaymentModal = true;
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
                'payment_number' => 'PAY-AR-' . date('YmdHis'),
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

        $this->showPaymentModal = false;
        $this->dispatch('notify', message: 'Pembayaran berhasil dicatat.', type: 'success');
        $this->reset(['paymentAmount', 'paymentNote', 'selectedOrderId']);
    }

    public function render()
    {
        // Get orders that are NOT fully paid (unpaid or partial)
        // AND status is confirmed (not cancelled)
        $invoices = Order::with('user')
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->whereNotIn('status', ['cancelled', 'pending']) // Pending orders usually not receivables yet until confirmed
            ->when($this->search, function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('guest_name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $totalReceivables = Order::whereIn('payment_status', ['unpaid', 'partial'])
            ->whereNotIn('status', ['cancelled', 'pending'])
            ->get()
            ->sum(function($order) {
                return $order->remaining_balance;
            });

        return view('livewire.finance.receivables', [
            'invoices' => $invoices,
            'totalReceivables' => $totalReceivables
        ]);
    }
}