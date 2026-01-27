<?php

namespace App\Livewire\Reports;

use App\Services\BusinessIntelligence;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Analisis & Laporan Stok')]
class StockReport extends Component
{
    public $reportData;

    public function mount()
    {
        $this->generateReport();
    }

    public function generateReport()
    {
        $bi = new BusinessIntelligence;
        $this->reportData = $bi->ambilAnalisisStok();
    }

    public function render()
    {
        return view('livewire.reports.stock-report');
    }
}