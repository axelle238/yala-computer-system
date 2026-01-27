<?php

namespace App\Livewire\Services;

use App\Models\Commission;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ServiceHistory;
use App\Models\ServiceItem;
use App\Models\ServiceTicket;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Service Workbench - Yala Computer')]
class Form extends Component
{
    public ?ServiceTicket $ticket = null;

    // Ticket Details
    public $ticket_number;

    public $customer_name;

    public $customer_phone;

    public $device_name;

    public $problem_description;

    public $status = 'pending';

    public $technician_notes;

    public $technician_id;

    // Costing
    public $estimated_cost = 0;

    public $labor_cost = 0; // Jasa Service

    // Parts Management
    public $parts = [];
    // Structure: ['id' => ?, 'product_id' => ?, 'name' => ?, 'qty' => ?, 'price' => ?, 'note' => ?, 'is_inventory' => ?, 'serial_number' => ?, 'warranty_duration' => ?]

    public $productSearch = '';

    // UI State
    public $activeTab = 'info'; // info, diagnosis, parts, history

    public $technicians = [];

    public function mount($id = null)
    {
        $this->technicians = User::where('role', '!=', 'customer')->orderBy('name')->get();

        if ($id) {
            $this->ticket = ServiceTicket::with(['sukuCadang.produk', 'riwayat.pengguna'])->findOrFail($id);
            $this->fill([
                'ticket_number' => $this->ticket->ticket_number,
                'customer_name' => $this->ticket->customer_name,
                'customer_phone' => $this->ticket->customer_phone,
                'device_name' => $this->ticket->device_name,
                'problem_description' => $this->ticket->problem_description,
                'status' => $this->ticket->status,
                'estimated_cost' => $this->ticket->estimated_cost,
                'technician_notes' => $this->ticket->technician_notes,
                'technician_id' => $this->ticket->technician_id,
                'labor_cost' => max(0, $this->ticket->final_cost - $this->ticket->sukuCadang->sum(fn ($i) => $i->price * $i->quantity)),
            ]);

            // Load existing items
            foreach ($this->ticket->sukuCadang as $item) {
                $this->parts[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->item_name,
                    'qty' => $item->quantity,
                    'price' => $item->price,
                    'note' => $item->note,
                    'is_inventory' => ! is_null($item->product_id),
                    'serial_number' => $item->serial_number,
                    'warranty_duration' => $item->warranty_duration,
                ];
            }
        } else {
            $this->ticket_number = 'SRV-'.date('Ymd').'-'.strtoupper(Str::random(4));
            $this->technician_id = auth()->id(); // Default to creator
        }
    }

    // --- Parts Logic ---
    public function addPart($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->parts[] = [
                'id' => null, // New item
                'product_id' => $product->id,
                'name' => $product->name,
                'qty' => 1,
                'price' => $product->sell_price,
                'note' => '',
                'is_inventory' => true,
                'serial_number' => '',
                'warranty_duration' => $product->warranty_period ?? 0, // Assuming Product model has this or default 0
            ];
            $this->productSearch = '';
            $this->activeTab = 'parts';
        }
    }

    public function addCustomItem()
    {
        $this->parts[] = [
            'id' => null,
            'product_id' => null,
            'name' => 'Biaya Tambahan / Sparepart Luar',
            'qty' => 1,
            'price' => 0,
            'note' => '',
            'is_inventory' => false,
            'serial_number' => '',
            'warranty_duration' => 0,
        ];
    }

    public function removePart($index)
    {
        unset($this->parts[$index]);
        $this->parts = array_values($this->parts);
    }

    // --- Calculations ---
    public function getTotalPartsCostProperty()
    {
        return array_sum(array_map(fn ($p) => $p['price'] * $p['qty'], $this->parts));
    }

    public function getGrandTotalProperty()
    {
        return $this->labor_cost + $this->totalPartsCost;
    }

    // --- Save & Print ---
    public function save()
    {
        $this->validate([
            'customer_name' => 'required',
            'device_name' => 'required',
            'status' => 'required',
        ], [
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
            'device_name.required' => 'Nama perangkat wajib diisi.',
            'status.required' => 'Status layanan wajib dipilih.',
        ]);

        try {
            DB::transaction(function () {
                $oldStatus = $this->ticket ? $this->ticket->status : null;
                $isNew = ! $this->ticket;

                // 1. Create/Update Ticket
                $data = [
                    'ticket_number' => $this->ticket_number,
                    'customer_name' => $this->customer_name,
                    'customer_phone' => $this->customer_phone,
                    'device_name' => $this->device_name,
                    'problem_description' => $this->problem_description,
                    'status' => $this->status,
                    'estimated_cost' => $this->estimated_cost,
                    'final_cost' => $this->grandTotal,
                    'technician_notes' => $this->technician_notes,
                    'technician_id' => $this->technician_id ?? auth()->id(),
                ];

                if ($isNew) {
                    $this->ticket = ServiceTicket::create($data);
                    // Initial History
                    ServiceHistory::create([
                        'service_ticket_id' => $this->ticket->id,
                        'user_id' => auth()->id(),
                        'status' => $this->status,
                        'notes' => 'Tiket dibuat.',
                    ]);
                } else {
                    $this->ticket->update($data);

                    // History Update if Status Changed
                    if ($oldStatus !== $this->status) {
                        ServiceHistory::create([
                            'service_ticket_id' => $this->ticket->id,
                            'user_id' => auth()->id(),
                            'status' => $this->status,
                            'notes' => 'Status berubah dari '.ucfirst($oldStatus).' ke '.ucfirst($this->status),
                        ]);
                    }
                }

                // 2. Handle Items Logic with Stock Deduction
                $existingItems = $this->ticket->sukuCadang()->get()->keyBy('id');
                $currentPartIds = collect($this->parts)->pluck('id')->filter()->toArray();

                // A. DELETE removed items
                foreach ($existingItems as $id => $item) {
                    if (! in_array($id, $currentPartIds)) {
                        if ($item->is_stock_deducted && $item->product_id) {
                            $this->adjustStock($item->product_id, $item->quantity, 'in', "Service Cancel Item #{$this->ticket->ticket_number}");
                        }
                        $item->delete();
                    }
                }

                // B. UPDATE or CREATE items
                foreach ($this->parts as $part) {
                    $itemData = [
                        'quantity' => $part['qty'],
                        'price' => $part['price'],
                        'note' => $part['note'],
                        'serial_number' => $part['serial_number'],
                        'warranty_duration' => $part['warranty_duration'],
                        // Don't update is_stock_deducted usually, but we keep it sync if needed logic changes
                    ];

                    if ($part['id'] && isset($existingItems[$part['id']])) {
                        // Update
                        $item = $existingItems[$part['id']];
                        $qtyDiff = $part['qty'] - $item->quantity;

                        if ($qtyDiff != 0 && $item->is_stock_deducted && $item->product_id) {
                            $type = $qtyDiff > 0 ? 'out' : 'in';
                            $this->adjustStock($item->product_id, abs($qtyDiff), $type, "Service Update Item #{$this->ticket->ticket_number}");
                        }

                        $item->update($itemData);

                    } else {
                        // Create
                        $isDeducted = false;
                        if ($part['product_id']) {
                            $this->adjustStock($part['product_id'], $part['qty'], 'out', "Service Usage #{$this->ticket->ticket_number}");
                            $isDeducted = true;
                        }

                        ServiceItem::create(array_merge($itemData, [
                            'service_ticket_id' => $this->ticket->id,
                            'product_id' => $part['product_id'],
                            'item_name' => $part['name'],
                            'is_stock_deducted' => $isDeducted,
                        ]));
                    }
                }

                // 3. Automated Commission
                if ($this->status === 'picked_up' && $oldStatus !== 'picked_up') {
                    $this->processCommission();
                }
            });

            session()->flash('success', 'Data servis berhasil disimpan.');
            // Refresh parts to get IDs
            $this->mount($this->ticket->id);

        } catch (\Exception $e) {
            $this->addError('parts', $e->getMessage());
        }
    }

    protected function processCommission()
    {
        $existingCommission = Commission::where('source_type', ServiceTicket::class)
            ->where('source_id', $this->ticket->id)
            ->first();

        if (! $existingCommission) {
            $percent = Setting::get('commission_service_percent', 10);
            $partsCost = $this->ticket->sukuCadang()->sum(DB::raw('price * quantity'));
            $laborCost = max(0, $this->ticket->final_cost - $partsCost);

            $commissionAmount = $laborCost * ($percent / 100);

            if ($commissionAmount > 0) {
                Commission::create([
                    'user_id' => $this->technician_id, // Assign to assigned tech, not auth()
                    'amount' => $commissionAmount,
                    'description' => "Komisi Service #{$this->ticket->ticket_number} ({$percent}%)",
                    'source_type' => ServiceTicket::class,
                    'source_id' => $this->ticket->id,
                    'is_paid' => false,
                ]);
            }
        }
    }

    protected function adjustStock($productId, $qty, $type, $reason)
    {
        $product = Product::lockForUpdate()->find($productId);
        if (! $product) {
            return;
        }

        if ($type === 'out') {
            if ($product->stock_quantity < $qty) {
                throw new \Exception("Stok {$product->name} tidak mencukupi! (Sisa: {$product->stock_quantity})");
            }
            $product->decrement('stock_quantity', $qty);
        } else {
            $product->increment('stock_quantity', $qty);
        }

        InventoryTransaction::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $type,
            'quantity' => $qty,
            'remaining_stock' => $product->stock_quantity,
            'unit_price' => $product->sell_price,
            'cogs' => $product->buy_price,
            'reference_number' => $this->ticket->ticket_number,
            'notes' => $reason,
        ]);
    }

    public function printInvoice()
    {
        $this->save();

        return redirect()->route('admin.cetak.servis', $this->ticket->id);
    }

    public function render()
    {
        $products = [];
        if (strlen($this->productSearch) > 2) {
            $products = Product::where('name', 'like', '%'.$this->productSearch.'%')
                ->orWhere('sku', 'like', '%'.$this->productSearch.'%')
                ->take(5)->get();
        }

        return view('livewire.services.form', [
            'products' => $products,
        ]);
    }
}
