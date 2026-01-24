<?php

namespace App\Livewire\Services;

use App\Models\Product;
use App\Models\ServiceHistory;
use App\Models\ServiceItem;
use App\Models\ServiceTicket;
use App\Models\InventoryTransaction; // Asumsi ada model ini untuk log stok
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

    // Parts Management
    public $partSearch = '';
    public $searchResults = [];
    public $selectedProduct = null;
    public $quantity = 1;

    public function mount($id)
    {
        $this->ticket = ServiceTicket::with(['items.product', 'histories.user', 'technician'])->findOrFail($id);
        $this->currentStatus = $this->ticket->status;
    }

    public function updatedPartSearch()
    {
        if (strlen($this->partSearch) > 2) {
            $this->searchResults = Product::where('name', 'like', '%' . $this->partSearch . '%')
                ->orWhere('sku', 'like', '%' . $this->partSearch . '%')
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct($productId)
    {
        $this->selectedProduct = Product::find($productId);
        $this->partSearch = '';
        $this->searchResults = [];
    }

    public function addPart()
    {
        if (!$this->selectedProduct) return;

        $this->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($this->selectedProduct->track_inventory && $this->selectedProduct->stock < $this->quantity) {
            $this->addError('quantity', 'Stok tidak mencukupi (Tersedia: ' . $this->selectedProduct->stock . ')');
            return;
        }

        DB::transaction(function () {
            // 1. Deduct Stock if track_inventory
            if ($this->selectedProduct->track_inventory) {
                $this->selectedProduct->decrement('stock', $this->quantity);
                $newStock = $this->selectedProduct->stock - $this->quantity; // Calculate manually for log to avoid extra query
                
                // Log Inventory Transaction
                InventoryTransaction::create([
                    'product_id' => $this->selectedProduct->id,
                    'user_id' => Auth::id(),
                    'warehouse_id' => 1, // Default Store Warehouse
                    'type' => 'out', // Service Usage
                    'quantity' => $this->quantity,
                    'unit_price' => $this->selectedProduct->price,
                    'cogs' => $this->selectedProduct->cost_price ?? 0,
                    'remaining_stock' => $newStock,
                    'reference_number' => $this->ticket->ticket_number,
                    'notes' => 'Used in Service Ticket #' . $this->ticket->ticket_number,
                ]);
            }

            // 2. Add to Service Items
            ServiceItem::create([
                'service_ticket_id' => $this->ticket->id,
                'product_id' => $this->selectedProduct->id,
                'item_name' => $this->selectedProduct->name,
                'quantity' => $this->quantity,
                'cost' => $this->selectedProduct->cost_price ?? 0,
                'price' => $this->selectedProduct->price, // Harga Jual ke Customer
                'is_stock_deducted' => $this->selectedProduct->track_inventory,
            ]);

            // 3. Update Ticket Estimate/Total if needed
            // $this->ticket->final_cost += ...
        });

        $this->selectedProduct = null;
        $this->quantity = 1;
        $this->ticket->refresh(); // Reload items
        session()->flash('success', 'Sparepart berhasil ditambahkan.');
    }

    public function removePart($itemId)
    {
        $item = ServiceItem::findOrFail($itemId);

        DB::transaction(function () use ($item) {
            // 1. Restore Stock
            if ($item->is_stock_deducted && $item->product_id) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // 2. Delete Item
            $item->delete();
        });

        $this->ticket->refresh();
        session()->flash('success', 'Sparepart dibatalkan dan stok dikembalikan.');
    }

    public function updateStatus($newStatus)
    {
        // Validasi alur status bisa ditambahkan di sini jika perlu
        
        $oldStatus = $this->ticket->status;
        $this->ticket->status = $newStatus;
        $this->ticket->save();

        ServiceHistory::create([
            'service_ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'status' => $newStatus,
            'notes' => $this->noteInput ?: "Status berubah dari $oldStatus ke $newStatus",
        ]);

        $this->noteInput = '';
        $this->currentStatus = $newStatus;
        $this->ticket->refresh();
        
        session()->flash('success', 'Status tiket diperbarui.');
    }

    public function render()
    {
        return view('livewire.services.workbench');
    }
}
