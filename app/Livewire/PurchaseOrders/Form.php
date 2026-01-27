<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Formulir Pesanan Pembelian - Yala Computer')]
class Form extends Component
{
    public ?PurchaseOrder $po = null;

    // Data Formulir
    public $nomor_po;

    public $id_pemasok;

    public $tanggal_pesanan;

    public $catatan;

    public $status = 'draft';

    // Logika Item
    public $item_pesanan = []; // [['id_produk' => '', 'qty' => 1, 'harga' => 0]]

    public function mount($id = null)
    {
        if ($id) {
            $this->po = PurchaseOrder::with('item')->findOrFail($id);
            $this->nomor_po = $this->po->po_number;
            $this->id_pemasok = $this->po->supplier_id;
            $this->tanggal_pesanan = $this->po->order_date->format('Y-m-d');
            $this->catatan = $this->po->notes;
            $this->status = $this->po->status;

            foreach ($this->po->item as $item) {
                $this->item_pesanan[] = [
                    'id_produk' => $item->product_id,
                    'qty' => $item->quantity_ordered,
                    'harga' => $item->buy_price,
                ];
            }
        } else {
            $this->nomor_po = 'PO-'.date('Ymd').'-'.strtoupper(Str::random(4));
            $this->tanggal_pesanan = date('Y-m-d');
            $this->item_pesanan[] = ['id_produk' => '', 'qty' => 1, 'harga' => 0]; // Baris kosong awal
        }
    }

    public function tambahItem()
    {
        $this->item_pesanan[] = ['id_produk' => '', 'qty' => 1, 'harga' => 0];
    }

    public function hapusItem($index)
    {
        unset($this->item_pesanan[$index]);
        $this->item_pesanan = array_values($this->item_pesanan);
    }

    public function perbaruiHarga($index)
    {
        $idProduk = $this->item_pesanan[$index]['id_produk'];
        if ($idProduk) {
            $produk = Product::find($idProduk);
            $this->item_pesanan[$index]['harga'] = $produk->buy_price;
        }
    }

    public function getTotalProperty()
    {
        $total = 0;
        foreach ($this->item_pesanan as $item) {
            $total += ((int)$item['qty'] * (int)$item['harga']);
        }

        return $total;
    }

    public function simpan()
    {
        // Jika sudah Dipesan/Diterima, tidak boleh edit Item, hanya header (catatan/tanggal)
        if ($this->po && in_array($this->po->status, ['ordered', 'received', 'partial'])) {
            $this->validate([
                'catatan' => 'nullable|string',
                'tanggal_pesanan' => 'required|date',
            ], [
                'tanggal_pesanan.required' => 'Tanggal pesanan wajib diisi.',
            ]);

            $this->po->update([
                'notes' => $this->catatan,
                'order_date' => $this->tanggal_pesanan,
            ]);

            $this->dispatch('notify', message: 'Info PO diperbarui. Item tidak dapat diubah karena status pesanan sudah berjalan.', type: 'info');

            return redirect()->route('admin.pesanan-pembelian.indeks');
        }

        $this->validate([
            'id_pemasok' => 'required',
            'tanggal_pesanan' => 'required|date',
            'item_pesanan.*.id_produk' => 'required',
            'item_pesanan.*.qty' => 'required|integer|min:1',
        ], [
            'id_pemasok.required' => 'Pemasok wajib dipilih.',
            'tanggal_pesanan.required' => 'Tanggal pesanan wajib diisi.',
            'item_pesanan.*.id_produk.required' => 'Produk wajib dipilih.',
            'item_pesanan.*.qty.required' => 'Jumlah wajib diisi.',
            'item_pesanan.*.qty.min' => 'Jumlah minimal 1.',
        ]);

        DB::transaction(function () {
            $data = [
                'po_number' => $this->nomor_po,
                'supplier_id' => $this->id_pemasok,
                'order_date' => $this->tanggal_pesanan,
                'notes' => $this->catatan,
                'status' => $this->status, // Pengguna bisa memilih draft atau ordered
                'total_amount' => $this->getTotalProperty(),
                'created_by' => auth()->id(),
            ];

            if ($this->po) {
                $this->po->update($data);
                $this->po->item()->delete();
            } else {
                $this->po = PurchaseOrder::create($data);
            }

            foreach ($this->item_pesanan as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $this->po->id,
                    'product_id' => $item['id_produk'],
                    'quantity_ordered' => $item['qty'],
                    'buy_price' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga'],
                ]);
            }
        });

        $this->dispatch('notify', message: 'Pesanan Pembelian berhasil disimpan.', type: 'success');

        return redirect()->route('admin.pesanan-pembelian.indeks');
    }

    public function tandaiDipesan()
    {
        if ($this->po && $this->po->status === 'draft') {
            $this->po->update(['status' => 'ordered']);
            $this->status = 'ordered';
            $this->dispatch('notify', message: 'PO disetujui & status diubah menjadi Dipesan (Ordered).', type: 'success');
        }
    }

    public function render()
    {
        // Filter produk berdasarkan pemasok jika dipilih
        $produk = Product::where('supplier_id', $this->id_pemasok)->get();
        if ($produk->isEmpty()) {
            $produk = Product::all(); // Fallback semua produk jika supplier belum punya produk atau belum dipilih
        }

        return view('livewire.purchase-orders.form', [
            'daftarPemasok' => Supplier::all(),
            'daftarProduk' => $produk,
        ]);
    }
}