<?php

namespace App\Livewire\Admin;

use App\Models\Peran;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Form Peran & Hak Akses')]
class RoleForm extends Component
{
    public $nama;

    public $hak_akses = [];

    public $peranId;

    public $isEdit = false;

    // Definisi Hak Akses yang tersedia (Grouping)
    public $permissionsList = [
        'Dashboard & Umum' => [
            'dashboard' => 'Akses Dashboard Utama',
        ],
        'Penjualan (POS)' => [
            'akses_pos' => 'Akses Mesin Kasir (POS)',
            'lihat_pesanan' => 'Lihat Riwayat Transaksi',
            'kelola_pesanan' => 'Kelola Pesanan (Refund/Batal)',
            'akses_penawaran' => 'Kelola Penawaran (B2B)',
        ],
        'Produk & Gudang' => [
            'akses_gudang' => 'Akses Menu Gudang Utama',
            'lihat_produk' => 'Lihat Daftar Produk',
            'tambah_produk' => 'Tambah Produk Baru',
            'edit_produk' => 'Edit Data Produk',
            'hapus_produk' => 'Hapus Produk',
            'stok_opname' => 'Lakukan Stok Opname',
            'akses_logistik' => 'Kelola Pengiriman & Kurir',
            'akses_procurement' => 'Kelola Pesanan Pembelian (PO)',
            'terima_barang' => 'Penerimaan Barang Masuk (GR)',
        ],
        'Servis & RMA' => [
            'akses_servis' => 'Akses Menu Servis',
            'lihat_tiket' => 'Lihat Tiket Servis',
            'buat_tiket' => 'Buat Tiket Baru',
            'update_progres' => 'Update Progres Servis',
            'akses_rma' => 'Kelola Klaim Garansi (RMA)',
            'akses_perakitan' => 'Akses Workbench Perakitan PC',
            'kelola_pengetahuan' => 'Kelola Basis Pengetahuan',
        ],
        'Keuangan' => [
            'akses_keuangan' => 'Akses Menu Keuangan',
            'lihat_laporan' => 'Lihat Laporan Bisnis',
            'kelola_kas' => 'Kelola Kas & Expenses',
            'analisa_profit' => 'Akses Analisa Profit/Laba',
        ],
        'CRM & Pemasaran (Media)' => [
            'akses_media' => 'Akses Menu Media & Pemasaran',
            'lihat_pelanggan' => 'Lihat Database Pelanggan',
            'kelola_pelanggan' => 'Edit Data Pelanggan',
            'akses_chat' => 'Akses Live Chat & Inbox',
            'kelola_berita' => 'Kelola Artikel & Berita',
            'kelola_spanduk' => 'Kelola Banner Promosi',
            'kelola_voucher' => 'Buat Voucher & Diskon',
            'kirim_wa' => 'Kirim Pesan Massal (WA Blast)',
        ],
        'SDM & Karyawan' => [
            'lihat_karyawan' => 'Lihat Data Karyawan',
            'kelola_karyawan' => 'Manajemen Karyawan & Shift',
            'kelola_peran' => 'Manajemen Hak Akses & Peran',
            'kelola_gaji' => 'Akses Data Penggajian',
        ],
        'Sistem & Administrator' => [
            'akses_admin' => 'Akses Penuh Administrator',
            'kelola_pengaturan' => 'Ubah Konfigurasi Sistem',
            'kelola_aset' => 'Manajemen Aset Perusahaan',
            'lihat_log' => 'Audit Log Aktivitas',
            'akses_sistem_info' => 'Lihat Informasi Server',
        ],
    ];

    public function mount($id = null)
    {
        if ($id) {
            $peran = Peran::findOrFail($id);
            $this->peranId = $id;
            $this->nama = $peran->nama;
            $this->hak_akses = $peran->hak_akses ?? [];
            $this->isEdit = true;
        } else {
            $this->hak_akses = [];
        }
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|unique:peran,nama,'.$this->peranId,
            'hak_akses' => 'array',
        ]);

        Peran::updateOrCreate(
            ['id' => $this->peranId],
            [
                'nama' => $this->nama,
                'hak_akses' => $this->hak_akses,
            ]
        );

        session()->flash('success', $this->isEdit ? 'Peran berhasil diperbarui.' : 'Peran baru berhasil dibuat.');

        return redirect()->route('admin.karyawan.peran.indeks');
    }

    public function render()
    {
        return view('livewire.admin.role-form');
    }
}
