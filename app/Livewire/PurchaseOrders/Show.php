<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\Expense;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Detail Purchase Order - Yala Computer')]
class Show extends Component
{
    public PurchaseOrder $po;

    public $receiveData = []; // ['item_id' => received_qty_input]

    public $activeAction = null; // null, 'receive'

    public function mount(PurchaseOrder $po)
    {
        $this->po = $po->load(['item.produk', 'pemasok', 'pembuat']);

        // Init form input
        foreach ($this->po->item as $item) {
            $this->receiveData[$item->id] = 0; // Default 0 saat modal dibuka
        }
    }

    public function openReceivePanel()
    {
        // Pre-fill dengan sisa yang belum diterima
        foreach ($this->po->item as $item) {
            $remaining = $item->quantity_ordered - $item->quantity_received;
            $this->receiveData[$item->id] = max(0, $remaining);
        }
        $this->activeAction = 'receive';
    }

    public function closeReceivePanel()
    {
        $this->activeAction = null;
        $this->reset('receiveData');
        // Re-init default values to avoid errors if reopened
        foreach ($this->po->item as $item) {
            $this->receiveData[$item->id] = 0;
        }
    }

    public function processReceiving()
    {
        $this->validate([
            'receiveData.*' => 'required|integer|min:0',
        ]);

        // Cek apakah ada input > 0
        $totalInput = array_sum($this->receiveData);
        if ($totalInput <= 0) {
            $this->dispatch('notify', message: 'Jumlah terima harus minimal satu barang.', type: 'error');

            return;
        }

        DB::transaction(function () {
            $totalCostReceived = 0;
            $allItemsFullyReceived = true;
            $atLeastOneReceived = false;

            foreach ($this->po->item as $item) {
                $qtyToReceive = (int) $this->receiveData[$item->id];
                $remaining = $item->quantity_ordered - $item->quantity_received;

                if ($qtyToReceive > $remaining) {
                    throw new \Exception("Jumlah terima untuk {$item->produk->name} melebihi sisa pesanan.");
                }

                if ($qtyToReceive > 0) {
                    $atLeastOneReceived = true;

                    // 1. Update PO Item
                    $item->increment('quantity_received', $qtyToReceive);

                    // 2. Update Product Stock & Average Cost Logic (Optional, here just updating last buy price)
                    $product = Product::lockForUpdate()->find($item->product_id);
                    $product->increment('stock_quantity', $qtyToReceive);
                    $product->update(['buy_price' => $item->buy_price]); // Update harga beli terbaru

                    // 3. Inventory Transaction
                    InventoryTransaction::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => 'in',
                        'quantity' => $qtyToReceive,
                        'remaining_stock' => $product->stock_quantity,
                        'reference_number' => $this->po->po_number,
                        'unit_price' => $item->buy_price,
                        'notes' => "Penerimaan Barang PO #{$this->po->po_number} (Partial)",
                    ]);

                    $totalCostReceived += ($qtyToReceive * $item->buy_price);
                }

                // Check global status
                if ($item->refresh()->quantity_received < $item->quantity_ordered) {
                    $allItemsFullyReceived = false;
                }
            }

            // 4. Update PO Status
            if ($allItemsFullyReceived) {
                $this->po->update(['status' => 'received']);
            } else {
                // Tetap 'ordered' tapi status parsial bisa ditangani di UI
            }

            // 5. Create Expense Record (Otomatis mencatat pengeluaran/hutang)
            // Asumsi: Pembelian Stok dianggap pengeluaran langsung atau hutang dagang.
            // Untuk MVP, kita catat sebagai Pengeluaran "Pembelian Stok"
            if ($totalCostReceived > 0) {
                Expense::create([
                    'category' => 'Pembelian Stok',
                    'title' => "Pembayaran PO #{$this->po->po_number} (Auto)", // Changed description to title
                    'amount' => $totalCostReceived,
                    'expense_date' => now(), // Changed transaction_date to expense_date
                    // 'reference_number' => $this->po->po_number, // Removed non-existent column
                    // 'status' => 'paid', // Removed non-existent column
                    'user_id' => auth()->id(),
                ]);
            }
        });

        $this->closeReceivePanel();
        $this->dispatch('notify', message: 'Barang berhasil diterima dan stok bertambah!', type: 'success');
        $this->mount($this->po); // Refresh data
    }

    public function markAsOrdered()
    {
        if ($this->po->status === 'draft') {
            $this->po->update(['status' => 'ordered']);
            $this->dispatch('notify', message: 'PO berhasil dikirim ke supplier (Status: Ordered)', type: 'success');
        }
    }

    public function render()
    {
        return view('livewire.purchase-orders.show');
    }
}
