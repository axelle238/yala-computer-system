<?php

namespace App\Livewire\Admin;

use App\Models\Peran;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Peran & Hak Akses')]
class RoleManager extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.role-manager', [
            'peranList' => Peran::withCount('pengguna')->paginate(10)
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
Livewire\Admin;

use App\Models\Peran;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Peran & Hak Akses')]
class RoleManager extends Component
{
    use WithPagination;

    public $nama, $hak_akses = [];
    public $peranId;
    public $isEdit = false;
    public $showModal = false;

    // Definisi Hak Akses yang tersedia (Grouping)
    public $permissionsList = [
        'Dashboard' => [
            'dashboard' => 'Akses Dashboard Utama',
        ],
        'Penjualan (POS)' => [
            'akses_pos' => 'Akses Mesin Kasir (POS)',
            'lihat_pesanan' => 'Lihat Riwayat Pesanan',
            'kelola_pesanan' => 'Kelola Pesanan (Update Status)',
        ],
        'Inventaris & Produk' => [
            'akses_gudang' => 'Akses Menu Gudang',
            'lihat_produk' => 'Lihat Daftar Produk',
            'tambah_produk' => 'Tambah Produk Baru',
            'edit_produk' => 'Edit Data Produk',
            'hapus_produk' => 'Hapus Produk',
            'stok_opname' => 'Lakukan Stok Opname',
        ],
        'Servis & Layanan' => [
            'akses_servis' => 'Akses Menu Servis',
            'lihat_tiket' => 'Lihat Tiket Servis',
            'buat_tiket' => 'Buat Tiket Baru',
            'update_progres' => 'Update Progres Servis',
        ],
        'Keuangan' => [
            'akses_keuangan' => 'Akses Menu Keuangan',
            'lihat_laporan' => 'Lihat Laporan Keuangan',
            'kelola_kas' => 'Kelola Kas & Expenses',
        ],
        'SDM & Karyawan' => [
            'lihat_karyawan' => 'Lihat Data Karyawan',
            'kelola_karyawan' => 'Tambah/Edit Karyawan',
            'kelola_gaji' => 'Kelola Penggajian (Payroll)',
        ],
        'CRM & Pelanggan' => [
            'lihat_pelanggan' => 'Lihat Data Pelanggan',
            'kelola_pelanggan' => 'Kelola Data Pelanggan',
            'akses_chat' => 'Akses Live Chat & Inbox',
        ],
        'Sistem' => [
            'akses_admin' => 'Akses Penuh Administrator',
            'kelola_pengaturan' => 'Ubah Pengaturan Sistem',
            'lihat_log' => 'Lihat Log Aktivitas',
        ]
    ];

    public function mount()
    {
        $this->hak_akses = [];
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.admin.role-manager', [
            'peranList' => Peran::withCount('pengguna')->paginate(10)
        ]);
    }

    public function create()
    {
        // dd('create called');
        $this->reset(['nama', 'hak_akses', 'peranId']);
        $this->hak_akses = []; // Ensure it's an array
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $peran = Peran::findOrFail($id);
        $this->peranId = $id;
        $this->nama = $peran->nama;
        $this->hak_akses = $peran->hak_akses ?? [];
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required|unique:peran,nama,' . $this->peranId,
            'hak_akses' => 'array'
        ]);

        Peran::updateOrCreate(
            ['id' => $this->peranId],
            [
                'nama' => $this->nama,
                'hak_akses' => $this->hak_akses
            ]
        );

        $this->showModal = false;
        $this->dispatch('notify', message: $this->isEdit ? 'Peran berhasil diperbarui.' : 'Peran baru berhasil dibuat.', type: 'success');
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
