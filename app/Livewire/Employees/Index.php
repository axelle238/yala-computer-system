<?php

namespace App\Livewire\Employees;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

    public function mount()
    {
        // Security Gate: Hanya Admin yang boleh masuk sini
        if (!Auth::user()->isAdmin()) {
            return abort(403, 'Akses Ditolak. Halaman ini khusus Administrator.');
        }
    }

    public function delete($id)
    {
        if ($id == Auth::id()) {
            return session()->flash('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        User::findOrFail($id)->delete();
        session()->flash('success', 'Pegawai berhasil dihapus.');
    }

    public function render()
    {
        $users = User::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.employees.index', [
            'users' => $users
        ]);
    }
}
