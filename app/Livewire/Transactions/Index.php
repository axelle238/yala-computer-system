<?php

namespace App\Livewire\Transactions;

use App\Models\InventoryTransaction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Laporan Transaksi - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $type = ''; // in, out, adjustment

    #[Url(history: true)]
    public $dateStart = '';

    #[Url(history: true)]
    public $dateEnd = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public function updatedDateStart()
    {
        $this->resetPage();
    }

    public function updatedDateEnd()
    {
        $this->resetPage();
    }

    public function exportCsv()
    {
        $fileName = 'laporan-transaksi-'.date('Y-m-d_H-i').'.csv';

        $transactions = $this->buildQuery()->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Tanggal', 'Reff No', 'Produk', 'SKU', 'Tipe', 'Jumlah', 'Sisa Stok', 'Petugas', 'Catatan'];

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $row) {
                fputcsv($file, [
                    $row->created_at->format('Y-m-d H:i'),
                    $row->reference_number ?? '-',
                    $row->product->name,
                    $row->product->sku,
                    ucfirst($row->type),
                    ($row->type == 'out' ? '-' : '+').$row->quantity,
                    $row->remaining_stock,
                    $row->user->name ?? 'System',
                    $row->notes,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function buildQuery()
    {
        return InventoryTransaction::with(['product', 'user'])
            ->when($this->search, function ($q) {
                $q->whereHas('product', function ($sub) {
                    $sub->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('sku', 'like', '%'.$this->search.'%');
                })->orWhere('reference_number', 'like', '%'.$this->search.'%');
            })
            ->when($this->type, function ($q) {
                $q->where('type', $this->type);
            })
            ->when($this->dateStart, function ($q) {
                $q->whereDate('created_at', '>=', $this->dateStart);
            })
            ->when($this->dateEnd, function ($q) {
                $q->whereDate('created_at', '<=', $this->dateEnd);
            })
            ->latest();
    }

    public function render()
    {
        return view('livewire.transactions.index', [
            'transactions' => $this->buildQuery()->paginate(15),
        ]);
    }
}
