<?php

namespace App\Livewire\Procurement\GoodsReceive;

use App\Models\GoodsReceive;
use App\Models\GoodsReceiveItem;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Goods Receive (Penerimaan Barang)')]
class Create extends Component
{
    public $purchaseOrderId;
    public $poNumber;
    public $notes;
    public $receiveDate;
    
    // Data Structure: 
    // [productId => ['name' => '...', 'ordered' => 10, 'received_prev' => 0, 'receiving' => 0, 'has_sn' => true, 'serials' => ['SN1', 'SN2']]]
    public $poItems = []; 

    public function mount($poId = null)
    {
        $this->receiveDate = date('Y-m-d');
        if ($poId) {
            $this->purchaseOrderId = $poId;
            $this->loadPoItems();
        }
    }

    public function updatedPurchaseOrderId()
    {
        $this->loadPoItems();
    }

    public function loadPoItems()
    {
        $po = PurchaseOrder::with(['items.product', 'supplier'])->find($this->purchaseOrderId);
        
        if (!$po) {
            $this->poItems = [];
            return;
        }

        $this->poNumber = $po->po_number;
        $this->poItems = [];

        foreach ($po->items as $item) {
            $remaining = $item->quantity_ordered - $item->quantity_received;
            if ($remaining <= 0) continue; // Skip fully received items

            $this->poItems[$item->product_id] = [
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'sku' => $item->product->sku,
                'qty_ordered' => $item->quantity_ordered,
                'qty_received_prev' => $item->quantity_received,
                'qty_receiving' => 0, // Default 0 to prevent accidental receive
                'has_serial_number' => (bool) $item->product->has_serial_number,
                'serials' => [], // Array of strings
            ];
        }
    }

    // Helper to generate empty serial inputs when quantity changes
    public function updatedPoItems($value, $key)
    {
        // Parse key like: 5.qty_receiving (product_id 5)
        $parts = explode('.', $key);
        if (count($parts) < 2) return;
        
        $productId = $parts[0];
        $field = $parts[1];

        if ($field === 'qty_receiving') {
            $item = $this->poItems[$productId];
            if ($item['has_serial_number']) {
                $qty = (int) $value;
                // Resize serials array
                $currentSerials = $item['serials'];
                // Adjust array size: if qty increased, add empty strings. If decreased, slice.
                if ($qty > count($currentSerials)) {
                    for ($i = count($currentSerials); $i < $qty; $i++) {
                        $currentSerials[] = '';
                    }
                } elseif ($qty < count($currentSerials)) {
                    $currentSerials = array_slice($currentSerials, 0, $qty);
                }
                $this->poItems[$productId]['serials'] = $currentSerials;
            }
        }
    }

    public function save()
    {
        $this->validate([
            'purchaseOrderId' => 'required|exists:purchase_orders,id',
            'receiveDate' => 'required|date',
            'poItems.*.qty_receiving' => 'required|numeric|min:0',
            // Nested validation for serials is tricky in Livewire dynamic arrays, doing manual check below
        ]);

        // Manual Validation logic
        foreach ($this->poItems as $pid => $item) {
            $qty = (int) $item['qty_receiving'];
            if ($qty > 0 && $item['has_serial_number']) {
                if (count(array_filter($item['serials'])) < $qty) {
                    $this->addError("poItems.$pid.serials", "Serial Number wajib diisi lengkap untuk {$item['name']}");
                    return;
                }
                // Check duplicates in input
                if (count(array_unique($item['serials'])) < count($item['serials'])) {
                    $this->addError("poItems.$pid.serials", "Serial Number duplikat terdeteksi di input {$item['name']}");
                    return;
                }
                // Check database duplicates
                $existing = ProductSerial::whereIn('serial_number', $item['serials'])
                    ->where('product_id', $pid)
                    ->exists();
                if ($existing) {
                    $this->addError("poItems.$pid.serials", "Salah satu Serial Number sudah ada di sistem untuk produk {$item['name']}");
                    return;
                }
            }
            
            // Check over-receiving
            $remaining = $item['qty_ordered'] - $item['qty_received_prev'];
            if ($qty > $remaining) {
                $this->addError("poItems.$pid.qty_receiving", "Jumlah terima melebihi pesanan ({$remaining})");
                return;
            }
        }

        // Process Saving
        DB::transaction(function () {
            // 1. Create Header
            $gr = GoodsReceive::create([
                'receive_number' => 'GR-' . date('Ymd') . '-' . rand(1000, 9999),
                'purchase_order_id' => $this->purchaseOrderId,
                'received_date' => $this->receiveDate,
                'received_by' => Auth::id(),
                'notes' => $this->notes
            ]);

            $poFullyReceived = true;

            foreach ($this->poItems as $pid => $item) {
                $qty = (int) $item['qty_receiving'];
                if ($qty <= 0) {
                    // Check if this item still has remaining items to determine PO status
                    $remaining = $item['qty_ordered'] - $item['qty_received_prev'];
                    if ($remaining > 0) $poFullyReceived = false;
                    continue; 
                }

                // 2. Create Detail
                $grItem = GoodsReceiveItem::create([
                    'goods_receive_id' => $gr->id,
                    'product_id' => $pid,
                    'quantity_received' => $qty,
                ]);

                // 3. Update Product Stock
                $product = Product::find($pid);
                $product->increment('stock_quantity', $qty);

                // 4. Handle Serials
                if ($item['has_serial_number']) {
                    foreach ($item['serials'] as $sn) {
                        ProductSerial::create([
                            'product_id' => $pid,
                            'serial_number' => $sn,
                            'status' => 'available',
                            'warehouse_id' => 1, // Default warehouse for now, later make dynamic
                            'goods_receive_item_id' => $grItem->id,
                            'cost_price' => $product->buy_price, // Inherit current buy price
                        ]);
                    }
                }

                // 5. Update PO Item received count
                $poItem = \App\Models\PurchaseOrderItem::where('purchase_order_id', $this->purchaseOrderId)
                    ->where('product_id', $pid)
                    ->first();
                $poItem->increment('quantity_received', $qty);

                // Check if this specific item is fully received
                if (($poItem->quantity_received + $qty) < $poItem->quantity_ordered) {
                    $poFullyReceived = false;
                }
            }

            // 6. Update PO Status
            $po = PurchaseOrder::find($this->purchaseOrderId);
            // Re-check all items from DB to be sure about PO status
            $po->refresh();
            $allReceived = true;
            foreach($po->items as $pi) {
                if ($pi->quantity_received < $pi->quantity_ordered) {
                    $allReceived = false;
                    break;
                }
            }

            $po->status = $allReceived ? 'received' : 'ordered'; // If partial, stay ordered (or add 'partial' enum if exists)
            $po->save();
        });

        $this->dispatch('notify', message: 'Penerimaan barang berhasil diproses!', type: 'success');
        return redirect()->route('admin.procurement.index'); // Adjust route later
    }

    public function render()
    {
        $openPOs = PurchaseOrder::whereIn('status', ['ordered', 'partial'])->get();
        return view('livewire.procurement.goods-receive.create', [
            'openPOs' => $openPOs
        ]);
    }
}