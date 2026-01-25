<?php

namespace App\Livewire\Admin;

use App\Models\Peran;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Peran & Hak Akses')]
class RoleManager extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.role-manager', [
            'peranList' => Peran::withCount('pengguna')->paginate(10),
        ]);
    }

    public function create()
    {
        return redirect()->route('employees.roles.create');
    }

    public function edit($id)
    {
        return redirect()->route('employees.roles.edit', $id);
    }

    public function delete($id)
    {
        $peran = Peran::findOrFail($id);
        if ($peran->pengguna()->exists()) {
            $this->dispatch('notify', message: 'Tidak dapat menghapus peran yang sedang digunakan oleh user.', type: 'error');

            return;
        }

        $peran->delete();
        $this->dispatch('notify', message: 'Peran berhasil dihapus.', type: 'success');
    }
}
