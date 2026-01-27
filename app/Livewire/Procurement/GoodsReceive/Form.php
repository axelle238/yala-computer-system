<?php

namespace App\Livewire\Procurement\GoodsReceive;

use App\Models\GoodsReceive;
use App\Models\GoodsReceiveItem;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Proses GRN')]
class Form extends Component
{
    public PurchaseOrder $po;

    public $items = []; // [product_id => [received_qty, serials[]]]

    public $receivedDate;

    public $notes;

    // UI State
    public $activeItemId = null;

    public $tempQty = 0;

    public $tempSerials = [];

    public function mount($poId)
    {
        $this->po = PurchaseOrder::with('items.product')->findOrFail($poId);
        $this->receivedDate = date('Y-m-d');

        // Initialize Items
        foreach ($this->po->items as $item) {
            $this->items[$item->product_id] = [
                'name' => $item->product->name,
                'ordered_qty' => $item->quantity,
                'has_serial' => $item->product->has_serial_number,
                'received_qty' => 0,
                'serials' => [],
            ];
        }
    }

    public function openItemModal($productId)
    {
        $this->activeItemId = $productId;
        $this->tempQty = $this->items[$productId]['received_qty'];
        $this->tempSerials = $this->items[$productId]['serials'];

        // Auto fill empty serials slots
        $diff = $this->tempQty - count($this->tempSerials);
        for ($i = 0; $i < $diff; $i++) {
            $this->tempSerials[] = '';
        }
    }

    public function updatedTempQty()
    {
        // Adjust serials array size based on qty
        $currentCount = count($this->tempSerials);
        $newCount = (int) $this->tempQty;

        if ($newCount > $currentCount) {
            for ($i = 0; $i < ($newCount - $currentCount); $i++) {
                $this->tempSerials[] = '';
            }
        } elseif ($newCount < $currentCount) {
            $this->tempSerials = array_slice($this->tempSerials, 0, $newCount);
        }
    }

    public function saveItem()
    {
        if ($this->items[$this->activeItemId]['has_serial']) {
            // Validate Serials
            $this->tempSerials = array_filter($this->tempSerials); // Remove empty
            if (count($this->tempSerials) != $this->tempQty) {
                $this->dispatch('notify', message: 'Harap isi semua Nomor Seri sesuai jumlah yang diterima!', type: 'error');

                return;
            }

            // Check Duplicate in DB
            $duplicates = ProductSerial::whereIn('serial_number', $this->tempSerials)->pluck('serial_number')->toArray();
            if (! empty($duplicates)) {
                $this->dispatch('notify', message: 'Serial Number berikut sudah ada: '.implode(', ', $duplicates), type: 'error');

                return;
            }

            // Check Duplicate in Input
            if (count($this->tempSerials) !== count(array_unique($this->tempSerials))) {
                $this->dispatch('notify', message: 'Ada Serial Number ganda di inputan Anda.', type: 'error');

                return;
            }
        }

        $this->items[$this->activeItemId]['received_qty'] = $this->tempQty;
        $this->items[$this->activeItemId]['serials'] = $this->tempSerials;
        $this->activeItemId = null;
    }

    public function submitGrn()
    {
        // Validation: Ensure at least one item received
        $totalReceived = collect($this->items)->sum('received_qty');
        if ($totalReceived <= 0) {
            $this->dispatch('notify', message: 'Belum ada barang yang diterima.', type: 'error');

            return;
        }

        DB::transaction(function () {
            // 1. Create GRN Header
            $grn = GoodsReceive::create([
                'grn_number' => 'GRN-'.date('Ymd').'-'.$this->po->id,
                'purchase_order_id' => $this->po->id,
                'received_by' => Auth::id(),
                'received_date' => $this->receivedDate,
                'notes' => $this->notes,
            ]);

            foreach ($this->items as $productId => $data) {
                if ($data['received_qty'] > 0) {
                    $product = Product::find($productId);

                    // 2. GRN Item
                    GoodsReceiveItem::create([
                        'goods_receive_id' => $grn->id,
                        'product_id' => $productId,
                        'quantity_received' => $data['received_qty'],
                    ]);

                    // 3. Serial Numbers
                    if ($data['has_serial']) {
                        foreach ($data['serials'] as $sn) {
                            ProductSerial::create([
                                'product_id' => $productId,
                                'warehouse_id' => 1,
                                'serial_number' => $sn,
                                'status' => 'available',
                                'purchase_order_id' => $this->po->id,
                                'buy_price' => $this->po->items->where('product_id', $productId)->first()->unit_price ?? 0,
                            ]);
                        }
                    }

                    // 4. Update Stock & Log Inventory
                    $product->increment('stock_quantity', $data['received_qty']);

                    InventoryTransaction::create([
                        'product_id' => $productId,
                        'user_id' => Auth::id(),
                        'warehouse_id' => 1,
                        'type' => 'in',
                        'quantity' => $data['received_qty'],
                        'remaining_stock' => $product->stock_quantity,
                        'reference_number' => $grn->grn_number,
                        'notes' => 'GRN from PO #'.$this->po->po_number,
                        'cogs' => $this->po->items->where('product_id', $productId)->first()->unit_price ?? 0,
                    ]);
                }
            }

            // 5. Update PO Status
            // Logic simple: If any received -> received (for now).
            // Advanced: Check if fully received.
            $allReceived = true;
            foreach ($this->po->items as $poItem) {
                $rec = $this->items[$poItem->product_id]['received_qty'] ?? 0;
                // Note: This logic assumes 1 GRN per PO fully. For partial, we need to sum previous GRNs.
                // Keeping it simple for this "Complex" step (1 GRN flow).
                if ($rec < $poItem->quantity) {
                    $allReceived = false;
                }
            }

            $this->po->update(['status' => $allReceived ? 'received' : 'partial']);
        });

        session()->flash('success', 'Barang berhasil diterima dan stok bertambah.');

        return redirect()->route('admin.pesanan-pembelian.indeks');
    }

    public function render()
    {
        return view('livewire.procurement.goods-receive.form');
    }
}
