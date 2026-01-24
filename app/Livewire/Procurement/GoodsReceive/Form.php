<?php

namespace App\Livewire\Procurement\GoodsReceive;

use App\Models\GoodsReceive;
use App\Models\GoodsReceiveItem;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Penerimaan Barang (GRN) - Yala Computer')]
class Form extends Component
{
    // PO Selection
    public $purchaseOrderId;
    public $selectedPo;
    
    // Header Inputs
    public $doNumber;
    public $receivedDate;
    public $notes;

    // Items Logic
    public $items = []; // [product_id => [name, ordered, received_previously, receiving_now, serials]]

    // SN Input Modal
    public $showSerialModal = false;
    public $currentSerialProductId = null;
    public $serialInput = ''; // Textarea content

    public function mount()
    {
        $this->receivedDate = date('Y-m-d');
    }

    public function updatedPurchaseOrderId($val)
    {
        if (!$val) {
            $this->selectedPo = null;
            $this->items = [];
            return;
        }

        $this->selectedPo = PurchaseOrder::with('items.product')->find($val);
        $this->loadItems();
    }

    public function loadItems()
    {
        $this->items = [];
        
        foreach ($this->selectedPo->items as $poItem) {
            // Calculate previously received qty
            $receivedQty = GoodsReceiveItem::whereHas('goodsReceive', function($q) {
                $q->where('purchase_order_id', $this->selectedPo->id);
            })
            ->where('product_id', $poItem->product_id)
            ->sum('qty_received');

            $remaining = max(0, $poItem->quantity - $receivedQty);

            if ($remaining > 0) {
                $this->items[$poItem->product_id] = [
                    'name' => $poItem->product->name,
                    'sku' => $poItem->product->sku,
                    'ordered' => $poItem->quantity,
                    'prev_received' => $receivedQty,
                    'receiving_now' => $remaining, // Default to remaining
                    'serials' => [], // Array of SNs
                    'requires_serial' => true // Assume all products track SN for now, or add check
                ];
            }
        }
    }

    // --- Serial Number Management ---

    public function openSerialModal($productId)
    {
        $this->currentSerialProductId = $productId;
        $existing = $this->items[$productId]['serials'] ?? [];
        $this->serialInput = implode("\n", $existing);
        $this->showSerialModal = true;
    }

    public function saveSerials()
    {
        if (!$this->currentSerialProductId) return;

        // Clean input: split by newline, trim, remove empty
        $rawLines = explode("\n", $this->serialInput);
        $cleanSerials = array_filter(array_map('trim', $rawLines));
        
        // Validate count against receiving qty
        $receivingQty = (int) $this->items[$this->currentSerialProductId]['receiving_now'];
        
        // Update state
        $this->items[$this->currentSerialProductId]['serials'] = array_values($cleanSerials);
        $this->showSerialModal = false;

        // Notify if count mismatch (just warning)
        if (count($cleanSerials) != $receivingQty) {
            $this->dispatch('notify', message: "Warning: Jumlah SN (" . count($cleanSerials) . ") tidak sama dengan Qty Terima ($receivingQty).", type: 'warning');
        }
    }

    public function updateQty($productId, $val)
    {
        $val = (int) $val;
        if ($val < 0) $val = 0;
        
        $max = $this->items[$productId]['ordered'] - $this->items[$productId]['prev_received'];
        if ($val > $max) {
            $val = $max;
            $this->dispatch('notify', message: "Tidak bisa terima lebih dari sisa pesanan ($max)", type: 'error');
        }

        $this->items[$productId]['receiving_now'] = $val;
    }

    public function save()
    {
        $this->validate([
            'purchaseOrderId' => 'required',
            'doNumber' => 'required|string|max:50',
            'receivedDate' => 'required|date',
            'items' => 'required|array|min:1'
        ]);

        // Validation: At least one item > 0
        $totalReceiving = collect($this->items)->sum('receiving_now');
        if ($totalReceiving <= 0) {
            $this->dispatch('notify', message: 'Qty terima minimal 1 item.', type: 'error');
            return;
        }

        // Strict SN Validation
        foreach ($this->items as $pid => $data) {
            $qty = (int) $data['receiving_now'];
            if ($qty > 0 && !empty($data['serials'])) {
                // Check count
                if (count($data['serials']) > $qty) {
                    $this->addError("items.$pid", "Jumlah SN (" . count($data['serials']) . ") melebihi Qty Terima ($qty).");
                    return;
                }
                
                // Check uniqueness in DB
                $existingSNs = ProductSerial::where('product_id', $pid)
                    ->whereIn('serial_number', $data['serials'])
                    ->pluck('serial_number')
                    ->toArray();

                if (!empty($existingSNs)) {
                    $this->dispatch('notify', message: "Serial Number berikut sudah terdaftar untuk produk ini: " . implode(', ', $existingSNs), type: 'error');
                    return;
                }

                // Check duplicates in input itself
                if (count($data['serials']) !== count(array_unique($data['serials']))) {
                    $this->dispatch('notify', message: "Ada duplikasi Serial Number pada input.", type: 'error');
                    return;
                }
            }
        }

        DB::transaction(function () {
            // 1. Create Header
            $grn = GoodsReceive::create([
                'purchase_order_id' => $this->purchaseOrderId,
                'received_by' => Auth::id(),
                'grn_number' => 'GRN-' . date('Ymd') . '-' . rand(100, 999),
                'supplier_do_number' => $this->doNumber,
                'received_date' => $this->receivedDate,
                'notes' => $this->notes,
                'status' => 'finalized'
            ]);

            // 2. Process Items
            foreach ($this->items as $pid => $data) {
                $qty = (int) $data['receiving_now'];
                if ($qty <= 0) continue;

                // Create Detail
                $grnItem = GoodsReceiveItem::create([
                    'goods_receive_id' => $grn->id,
                    'product_id' => $pid,
                    'qty_ordered_snapshot' => $data['ordered'],
                    'qty_received' => $qty,
                ]);

                // Update Stock
                $product = Product::lockForUpdate()->find($pid);
                $oldStock = $product->stock_quantity;
                $product->increment('stock_quantity', $qty);

                // Insert Transaction Log
                InventoryTransaction::create([
                    'product_id' => $pid,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $qty,
                    'remaining_stock' => $product->stock_quantity,
                    'reference_number' => $grn->grn_number,
                    'notes' => 'Received from PO #' . $this->selectedPo->po_number
                ]);

                // Insert Serial Numbers
                if (!empty($data['serials'])) {
                    foreach ($data['serials'] as $sn) {
                        ProductSerial::create([
                            'product_id' => $pid, 
                            'serial_number' => $sn,
                            'goods_receive_id' => $grn->id,
                            'status' => 'available'
                        ]);
                    }
                }
            }

            // 3. Update PO Status
            // Check if fully received
            $allCompleted = true;
            foreach ($this->selectedPo->items as $poItem) {
                $totalReceived = GoodsReceiveItem::whereHas('goodsReceive', fn($q) => $q->where('purchase_order_id', $this->selectedPo->id))
                    ->where('product_id', $poItem->product_id)
                    ->sum('qty_received');
                
                // Note: The above query sees committed data if outside transaction, but here we are inside. 
                // However, we just inserted into GoodsReceiveItem, so it SHOULD be visible to the same transaction connection.
                
                if ($totalReceived < $poItem->quantity) {
                    $allCompleted = false;
                    break;
                }
            }

            $this->selectedPo->update([
                'delivery_status' => $allCompleted ? 'received' : 'partial',
                'status' => $allCompleted ? 'received' : 'ordered'
            ]);
        });

        $this->dispatch('notify', message: 'Penerimaan Barang Berhasil Disimpan!', type: 'success');
        return redirect()->route('purchase-orders.index');
    }

    public function render()
    {
        // Get Open POs (Ordered but not fully received)
        $purchaseOrders = PurchaseOrder::where('status', 'ordered')
            ->where(function($q) {
                $q->where('delivery_status', '!=', 'received')
                  ->orWhereNull('delivery_status');
            })
            ->with('supplier')
            ->get();

        return view('livewire.procurement.goods-receive.form', [
            'purchaseOrders' => $purchaseOrders
        ]);
    }
}
