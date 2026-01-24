<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Laporan Penjualan Detail - Yala Computer')]
class SalesReport extends Component
{
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    public function exportCsv()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=sales_report_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $start = $this->startDate . ' 00:00:00';
        $end = $this->endDate . ' 23:59:59';

        $items = OrderItem::with(['order', 'product'])
            ->whereHas('order', function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->where('status', 'completed');
            })
            ->latest()
            ->get();

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Date', 'Product', 'SKU', 'Qty', 'Unit Price', 'Total', 'Customer']);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->order->order_number,
                    $item->order->created_at->format('Y-m-d H:i'),
                    $item->product->name,
                    $item->product->sku,
                    $item->quantity,
                    $item->price,
                    $item->quantity * $item->price,
                    $item->order->guest_name ?? $item->order->user->name ?? 'Guest'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $sales = OrderItem::with(['order', 'product'])
            ->whereHas('order', function($q) {
                $q->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
                  ->where('status', 'completed');
            })
            ->latest()
            ->paginate(20);

        return view('livewire.reports.sales-report', [
            'sales' => $sales
        ]);
    }
}
