<?php

namespace App\Livewire\Reports;

use App\Services\BusinessIntelligence;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Laporan Keuangan & Laba Rugi')]
class FinanceReport extends Component
{
    public $month;
    public $year;
    public $reportData;

    public function mount()
    {
        $this->month = date('n');
        $this->year = date('Y');
        $this->generateReport();
    }

    public function updatedMonth() { $this->generateReport(); }
    public function updatedYear() { $this->generateReport(); }

    public function generateReport()
    {
        $bi = new BusinessIntelligence();
        $this->reportData = $bi->getProfitLoss($this->month, $this->year);
    }

    public function render()
    {
        return view('livewire.reports.finance-report');
    }
}