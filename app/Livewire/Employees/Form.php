<?php

namespace App\Livewire\Employees;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form User - Yala Computer')]
class Form extends Component
{
    public ?User $user = null;

    public $name = '';
    public $email = '';
    public $password = ''; 
    public $role = 'employee';
    public $base_salary = 0;
    public $join_date = '';
    public $selectedPermissions = [];

    public $availablePermissions = [
        'view_dashboard' => 'Melihat Dashboard',
        'manage_products' => 'Kelola Produk (Tambah/Edit)',
        'view_products' => 'Lihat Produk & Stok',
        'create_transaction' => 'Catat Transaksi (Kasir)',
        'view_reports' => 'Lihat Laporan Transaksi',
        'manage_settings' => 'Akses Pengaturan (Bahaya)',
        'manage_stock' => 'Kelola Stok & Pembelian',
    ];

    public function mount($id = null)
    {
        if (!Auth::user()->isAdmin()) {
            return abort(403);
        }

        if ($id) {
            $this->user = User::findOrFail($id);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->role = $this->user->role;
            $this->base_salary = $this->user->base_salary;
            $this->join_date = $this->user->join_date ? $this->user->join_date->format('Y-m-d') : '';
            $this->selectedPermissions = $this->user->access_rights ?? [];
        } else {
            $this->join_date = date('Y-m-d');
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id ?? null)],
            'role' => 'required|in:admin,owner,employee',
            'password' => $this->user ? 'nullable|min:6' : 'required|min:6',
            'base_salary' => 'numeric|min:0',
            'join_date' => 'nullable|date',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'base_salary' => $this->base_salary,
            'join_date' => $this->join_date,
            'access_rights' => $this->role === 'employee' ? $this->selectedPermissions : null,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->user) {
            $this->user->update($data);
            session()->flash('success', 'Data user diperbarui.');
        } else {
            User::create($data);
            session()->flash('success', 'User baru berhasil dibuat.');
        }

        return redirect()->route('employees.index');
    }

    public function render()
    {
        return view('livewire.employees.form');
    }
}