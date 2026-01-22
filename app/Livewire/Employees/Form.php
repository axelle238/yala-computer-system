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
    public $password = ''; // Only for creation or reset
    public $role = 'employee';
    public $selectedPermissions = [];

    // Daftar Izin yang tersedia untuk Pegawai
    public $availablePermissions = [
        'view_dashboard' => 'Melihat Dashboard',
        'manage_products' => 'Kelola Produk (Tambah/Edit)',
        'view_products' => 'Lihat Produk & Stok',
        'create_transaction' => 'Catat Transaksi (Kasir)',
        'view_reports' => 'Lihat Laporan Transaksi',
        'manage_settings' => 'Akses Pengaturan (Bahaya)',
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
            $this->selectedPermissions = $this->user->access_rights ?? [];
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id ?? null)],
            'role' => 'required|in:admin,owner,employee',
            'password' => $this->user ? 'nullable|min:6' : 'required|min:6',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            // Jika role bukan employee, permissions null (admin full, owner view only default)
            // Jika employee, simpan array permissions
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
