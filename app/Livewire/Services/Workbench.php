<?php

namespace App\Livewire\Services;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\Product;
use App\Models\ServiceTicket;
use App\Models\ServiceTicketPart;
use App\Models\ServiceTicketProgress;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Technician Workbench')]
class Workbench extends Component
{
    public ServiceTicket $ticket;
    
    // Status Management
    public $currentStatus;
    public $noteInput = '';
    public $isPublicNote = true;

    // Parts Management
    public $partSearch = '';
    public $searchResults = [];
    public $selectedProduct = null;
    public $quantity = 1;
    public $customPrice = 0;
    public $serialNumberOut = '';

    // Payment Logic
    public $showPaymentModal = false;
    public $paymentMethod = 'cash'; // cash, transfer
    public $paymentNote = '';

    public function mount($id)
    {
        $this->ticket = ServiceTicket::with([
            'parts.product', 
            'progressLogs',
            'technician',
            'customerMember'
        ])->findOrFail($id);
        
        $this->currentStatus = $this->ticket->status;
    }

    public function updatedPartSearch()
    {
        if (strlen($this->partSearch) > 2) {
            $this->searchResults = Product::with('category')
                ->where('name', 'like', '%' . $this->partSearch . '%')
                ->orWhere('sku', 'like', '%' . $this->partSearch . '%')
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct($productId)
    {
        $this->selectedProduct = Product::with('category')->find($productId);
        if ($this->selectedProduct) {
            $this->customPrice = $this->selectedProduct->sell_price; 
        }
        $this->partSearch = '';
        $this->searchResults = [];
    }

    private function isTracked($product)
    {
        if ($product->category && $product->category->slug === 'services') {
            return false;
        }
        return true;
    }

    public function addPart()
    {
        if (!$this->selectedProduct) return;

        $this->validate([
            'quantity' => 'required|integer|min:1',
            'customPrice' => 'required|numeric|min:0',
        ]);

        $isTracked = $this->isTracked($this->selectedProduct);

        if ($isTracked && $this->selectedProduct->stock_quantity < $this->quantity) {
            $this->addError('quantity', 'Stok tidak mencukupi (Tersedia: ' . $this->selectedProduct->stock_quantity . ')');
            return;
        }

        DB::transaction(function () use ($isTracked) {
            if ($isTracked) {
                $this->selectedProduct->decrement('stock_quantity', $this->quantity);
                
                InventoryTransaction::create([
                    'product_id' => $this->selectedProduct->id,
                    'user_id' => Auth::id() ?? 1,
                    'warehouse_id' => 1,
                    'type' => 'out',
                    'quantity' => $this->quantity,
                    'unit_price' => $this->customPrice,
                    'cogs' => $this->selectedProduct->buy_price ?? 0,
                    'remaining_stock' => $this->selectedProduct->stock_quantity, 
                    'reference_number' => $this->ticket->ticket_number,
                    'notes' => 'Used in Service Ticket #' . $this->ticket->ticket_number,
                ]);
            }

            ServiceTicketPart::create([
                'service_ticket_id' => $this->ticket->id,
                'product_id' => $this->selectedProduct->id,
                'quantity' => $this->quantity,
                'price_per_unit' => $this->customPrice,
                'subtotal' => $this->quantity * $this->customPrice,
            ]);
        });

        $this->selectedProduct = null;
        $this->quantity = 1;
        $this->customPrice = 0;
        
        $this->ticket->refresh();
        session()->flash('success', 'Sparepart berhasil ditambahkan.');
    }

    public function removePart($partId)
    {
        $part = ServiceTicketPart::findOrFail($partId);

        DB::transaction(function () use ($part) {
            $product = Product::with('category')->find($part->product_id);
            $isTracked = $product && $this->isTracked($product);

            if ($isTracked) {
                $product->increment('stock_quantity', $part->quantity);

                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id() ?? 1,
                    'warehouse_id' => 1,
                    'type' => 'in',
                    'quantity' => $part->quantity,
                    'unit_price' => $part->price_per_unit,
                    'cogs' => $product->buy_price ?? 0,
                    'remaining_stock' => $product->stock_quantity,
                    'reference_number' => $this->ticket->ticket_number,
                    'notes' => 'Removed/Returned from Service Ticket #' . $this->ticket->ticket_number,
                ]);
            }

            $part->delete();
        });

        $this->ticket->refresh();
        session()->flash('success', 'Sparepart dibatalkan dan stok dikembalikan.');
    }

    public function saveProgress()
    {
        $this->validate([
            'noteInput' => 'required|string|min:3',
        ]);

        ServiceTicketProgress::create([
            'service_ticket_id' => $this->ticket->id,
            'status_label' => $this->ticket->status,
            'description' => $this->noteInput,
            'technician_id' => Auth::id() ?? 1,
            'is_public' => $this->isPublicNote,
        ]);

        $this->noteInput = '';
        $this->ticket->refresh();
        session()->flash('success', 'Catatan progres tersimpan.');
    }

    public function updateStatus($newStatus)
    {
        if ($this->ticket->status === $newStatus) return;

        $oldStatus = $this->ticket->status;
        $this->ticket->status = $newStatus;
        $this->ticket->save();

        ServiceTicketProgress::create([
            'service_ticket_id' => $this->ticket->id,
            'status_label' => $newStatus,
            'description' => "Status diubah dari " . ucfirst(str_replace('_', ' ', $oldStatus)) . " ke " . ucfirst(str_replace('_', ' ', $newStatus)),
            'technician_id' => Auth::id() ?? 1,
            'is_public' => true,
        ]);

        $this->currentStatus = $newStatus;
        $this->ticket->refresh();
        session()->flash('success', 'Status tiket diperbarui.');
    }

    public function processPayment()
    {
        // 1. Cek Kasir Aktif
        $activeRegister = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();
        
        if (!$activeRegister) {
            $this->addError('payment', 'Anda belum membuka sesi kasir! Silakan buka kasir di menu Keuangan.');
            return;
        }

        // 2. Hitung Total
        $total = $this->ticket->parts->sum('subtotal');
        if ($total <= 0) {
            $this->addError('payment', 'Total tagihan Rp 0. Pastikan sudah input jasa/sparepart.');
            return;
        }

        DB::transaction(function () use ($activeRegister, $total) {
            // Catat Transaksi Masuk
            CashTransaction::create([
                'cash_register_id' => $activeRegister->id,
                'transaction_number' => 'PAY-' . date('ymd') . '-' . $this->ticket->id,
                'type' => 'in',
                'category' => 'service_payment', // Kategori Pendapatan Servis
                'amount' => $total,
                'description' => "Pembayaran Servis Tiket #{$this->ticket->ticket_number}. " . $this->paymentNote,
                'reference_id' => $this->ticket->id,
                'reference_type' => ServiceTicket::class,
                'created_by' => Auth::id(),
            ]);

            // Update Status Tiket
            $this->ticket->status = 'picked_up';
            $this->ticket->final_cost = $total;
            $this->ticket->save();

            // Log Progress
            ServiceTicketProgress::create([
                'service_ticket_id' => $this->ticket->id,
                'status_label' => 'picked_up',
                'description' => "Pembayaran diterima (Rp " . number_format($total) . ") via " . ucfirst($this->paymentMethod) . ". Unit diambil.",
                'technician_id' => Auth::id(),
                'is_public' => true,
            ]);
        });

        $this->showPaymentModal = false;
        $this->currentStatus = 'picked_up';
        $this->ticket->refresh();
        session()->flash('success', 'Pembayaran berhasil diproses. Tiket selesai.');
    }

    public function render()
    {
        return view('livewire.services.workbench');
    }
}