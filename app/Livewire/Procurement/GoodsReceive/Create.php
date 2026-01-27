<?php

namespace App\Livewire\Procurement\GoodsReceive;

use App\Models\GoodsReceive;
use App\Models\GoodsReceiveItem;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Receive Goods')]
class Create extends Component
{
    public $poId;

    public $purchaseOrder;

    // Form Inputs
    public $grnNumber;

    public $supplierDoNumber;

    public $receivedDate;

    public $notes;

    // Items Grid
    public $items = []; // [product_id => [name, ordered, received_prev, input_now]]

    public function mount()
    {
        $this->grnNumber = 'GRN-'.date('Ymd').'-'.rand(100, 999);
        $this->receivedDate = date('Y-m-d');

        if (request()->has('po_id')) {
            $this->poId = request()->query('po_id');
            $this->loadPo();
        }
    }

    public function updatedPoId()
    {
        $this->loadPo();
    }

    public function loadPo()
    {
        $this->purchaseOrder = PurchaseOrder::with(['items.product', 'supplier'])->find($this->poId);

        if (! $this->purchaseOrder) {
            $this->items = [];

            return;
        }

        $this->items = [];
        foreach ($this->purchaseOrder->items as $poItem) {
            $remaining = max(0, $poItem->quantity_ordered - $poItem->quantity_received);

            $this->items[] = [
                'po_item_id' => $poItem->id,
                'product_id' => $poItem->product_id,
                'product_name' => $poItem->product->name,
                'sku' => $poItem->product->sku,
                'qty_ordered' => $poItem->quantity_ordered,
                'qty_received_prev' => $poItem->quantity_received,
                'qty_input' => $remaining,
                'max_input' => $remaining,
            ];
        }
    }

    public function save()
    {
        $this->validate([
            'poId' => 'required|exists:purchase_orders,id',
            'grnNumber' => 'required|unique:goods_receives,grn_number',
            'receivedDate' => 'required|date',
            'supplierDoNumber' => 'required|string',
            'items.*.qty_input' => 'required|integer|min:0',
        ]);

        $totalReceived = collect($this->items)->sum('qty_input');
        if ($totalReceived <= 0) {
            $this->addError('global', 'Minimal satu barang harus diterima.');

            return;
        }

        DB::transaction(function () {
            // 1. Create Header
            $gr = GoodsReceive::create([
                'purchase_order_id' => $this->purchaseOrder->id,
                'grn_number' => $this->grnNumber,
                'supplier_do_number' => $this->supplierDoNumber,
                'received_date' => $this->receivedDate,
                'notes' => $this->notes,
                'received_by' => Auth::id() ?? 1,
                'status' => 'finalized',
            ]);

            // 2. Process Items
            foreach ($this->items as $itemData) {
                if ($itemData['qty_input'] > 0) {
                    $qtyReceivedNow = $itemData['qty_input'];
                    $productId = $itemData['product_id'];

                    GoodsReceiveItem::create([
                        'goods_receive_id' => $gr->id,
                        'product_id' => $productId,
                        'qty_ordered_snapshot' => $itemData['qty_ordered'],
                        'qty_received' => $qtyReceivedNow,
                    ]);

                    $poItem = PurchaseOrderItem::find($itemData['po_item_id']);
                    $poItem->increment('quantity_received', $qtyReceivedNow);

                    $product = Product::find($productId);
                    $product->increment('stock_quantity', $qtyReceivedNow);

                    InventoryTransaction::create([
                        'product_id' => $productId,
                        'user_id' => Auth::id() ?? 1,
                        'warehouse_id' => 1,
                        'type' => 'in',
                        'quantity' => $qtyReceivedNow,
                        'remaining_stock' => $product->stock_quantity,
                        'unit_price' => $product->buy_price,
                        'reference_number' => $this->grnNumber,
                        'notes' => 'Received via PO #'.$this->purchaseOrder->po_number,
                    ]);
                }
            }

            // 3. Update PO Delivery Status Logic
            $this->purchaseOrder->refresh();

            $allReceived = true;
            $anyReceived = false;

            foreach ($this->purchaseOrder->items as $item) {
                if ($item->quantity_received > 0) {
                    $anyReceived = true;
                }
                if ($item->quantity_received < $item->quantity_ordered) {
                    $allReceived = false;
                }
            }

            if ($allReceived) {
                $this->purchaseOrder->status = 'received';
                $this->purchaseOrder->delivery_status = 'received';
            } elseif ($anyReceived) {
                $this->purchaseOrder->status = 'ordered'; // Still open
                $this->purchaseOrder->delivery_status = 'partial';
            } else {
                $this->purchaseOrder->status = 'ordered';
                $this->purchaseOrder->delivery_status = 'pending';
            }

            $this->purchaseOrder->save();
        });

        session()->flash('success', 'Barang berhasil diterima dan stok diperbarui.');

        return redirect()->route('admin.pesanan-pembelian.tampil', $this->poId);
    }

    public function render()
    {
        // Cari PO yang belum fully received
        $openPOs = PurchaseOrder::whereIn('status', ['ordered'])
            ->where('delivery_status', '!=', 'received')
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.procurement.goods-receive.create', [
            'openPOs' => $openPOs,
        ]);
    }
}
