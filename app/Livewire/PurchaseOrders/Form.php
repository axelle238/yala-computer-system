<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form PO - Yala Computer')]
class Form extends Component
{
    public ?PurchaseOrder $po = null;

    // Form Fields
    public $po_number;
    public $supplier_id;
    public $order_date;
    public $notes;
    public $status = 'draft';

    // Items Logic
    public $items = []; // [['product_id' => '', 'qty' => 1, 'price' => 0]]

    public function mount($id = null)
    {
        if ($id) {
            $this->po = PurchaseOrder::with('items')->findOrFail($id);
            $this->po_number = $this->po->po_number;
            $this->supplier_id = $this->po->supplier_id;
            $this->order_date = $this->po->order_date->format('Y-m-d');
            $this->notes = $this->po->notes;
            $this->status = $this->po->status;

            foreach ($this->po->items as $item) {
                $this->items[] = [
                    'product_id' => $item->product_id,
                    'qty' => $item->quantity_ordered,
                    'price' => $item->buy_price,
                ];
            }
        } else {
            $this->po_number = 'PO-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            $this->order_date = date('Y-m-d');
            $this->items[] = ['product_id' => '', 'qty' => 1, 'price' => 0]; // Empty row
        }
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'qty' => 1, 'price' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatePrice($index)
    {
        $productId = $this->items[$index]['product_id'];
        if ($productId) {
            $product = Product::find($productId);
            $this->items[$index]['price'] = $product->buy_price;
        }
    }

    public function getTotalProperty()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += ($item['qty'] * $item['price']);
        }
        return $total;
    }

    public function save()
    {
        // Jika sudah Ordered/Received, tidak boleh edit Items, hanya header (notes/date)
        if ($this->po && in_array($this->po->status, ['ordered', 'received', 'partial'])) {
             $this->validate([
                'notes' => 'nullable|string',
                'order_date' => 'required|date',
            ]);
            
            $this->po->update([
                'notes' => $this->notes,
                'order_date' => $this->order_date,
            ]);
            
            session()->flash('success', 'Info PO diperbarui. Item tidak dapat diubah karena status sudah berjalan.');
            return redirect()->route('purchase-orders.index');
        }

        $this->validate([
            'supplier_id' => 'required',
            'order_date' => 'required|date',
            'items.*.product_id' => 'required',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::transaction(function () {
            $data = [
                'po_number' => $this->po_number,
                'supplier_id' => $this->supplier_id,
                'order_date' => $this->order_date,
                'notes' => $this->notes,
                'status' => $this->status, // User can select draft or ordered initially
                'total_amount' => $this->getTotalProperty(),
                'created_by' => auth()->id(),
            ];

            if ($this->po) {
                $this->po->update($data);
                $this->po->items()->delete(); 
            } else {
                $this->po = PurchaseOrder::create($data);
            }

            foreach ($this->items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $this->po->id,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['qty'],
                    'buy_price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price'],
                ]);
            }
        });

        session()->flash('success', 'Purchase Order berhasil disimpan.');
        return redirect()->route('purchase-orders.index');
    }
    
    public function markAsOrdered()
    {
        if ($this->po && $this->po->status === 'draft') {
            $this->po->update(['status' => 'ordered']);
            $this->status = 'ordered';
            session()->flash('success', 'PO disetujui & dikirim ke Supplier (Status: Ordered).');
        }
    }

    public function render()
    {
        $products = Product::where('supplier_id', $this->supplier_id)->get(); // Filter produk by supplier
        if ($products->isEmpty()) {
            $products = Product::all(); // Fallback all products
        }

        return view('livewire.purchase-orders.form', [
            'suppliers' => Supplier::all(),
            'products' => $products
        ]);
    }
}
