<?php

namespace App\Livewire\Employees;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Pegawai - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $employees = User::where('role', '!=', 'customer')
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.employees.index', [
            'employees' => $employees
        ]);
    }
}
