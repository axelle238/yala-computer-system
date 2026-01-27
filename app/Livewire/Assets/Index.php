<?php

namespace App\Livewire\Assets;

use App\Models\CompanyAsset;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Aset Tetap - Yala Computer')]
class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Filter & UI
    public $cari = '';
    public $tampilkanForm = false;
    public $tampilkanInfoDepresiasi = false;

    // Properti Formulir
    public $idAset;
    public $nama;
    public $nomorSeri;
    public $tanggalBeli;
    public $biayaBeli;
    public $umurEkonomisTahun;
    public $kondisi = 'Baik';
    public $lokasi;
    public $gambar;

    // Data Kalkulasi (Untuk Modal/Info)
    public $asetTerpilih;
    public $jadwalDepresiasi = [];

    /**
     * Reset pagination saat melakukan pencarian.
     */
    public function updatingCari()
    {
        $this->resetPage();
    }

    /**
     * Membuka formulir tambah aset baru.
     */
    public function buat()
    {
        $this->reset(['idAset', 'nama', 'nomorSeri', 'tanggalBeli', 'biayaBeli', 'umurEkonomisTahun', 'kondisi', 'lokasi', 'gambar']);
        $this->tampilkanForm = true;
        $this->tampilkanInfoDepresiasi = false;
    }

    /**
     * Menyimpan data aset ke database.
     */
    public function simpan()
    {
        $this->validate([
            'nama' => 'required|string|min:3',
            'tanggalBeli' => 'required|date',
            'biayaBeli' => 'required|numeric|min:0',
            'umurEkonomisTahun' => 'required|integer|min:1',
        ], [
            'nama.required' => 'Nama aset wajib diisi.',
            'nama.min' => 'Nama aset minimal 3 karakter.',
            'tanggalBeli.required' => 'Tanggal pembelian wajib diisi.',
            'biayaBeli.required' => 'Biaya pembelian wajib diisi.',
            'umurEkonomisTahun.required' => 'Umur ekonomis wajib diisi.',
        ]);

        $jalurGambar = null;
        if ($this->gambar) {
            $jalurGambar = $this->gambar->store('assets', 'public');
        }

        $nilaiSaatIni = $this->hitungNilaiSekarang($this->biayaBeli, $this->tanggalBeli, $this->umurEkonomisTahun);

        $data = [
            'name' => $this->nama,
            'serial_number' => $this->nomorSeri,
            'purchase_date' => $this->tanggalBeli,
            'purchase_cost' => $this->biayaBeli,
            'useful_life_years' => $this->umurEkonomisTahun,
            'condition' => $this->kondisi,
            'location' => $this->lokasi,
            'current_value' => $nilaiSaatIni,
        ];

        if ($jalurGambar) {
            $data['image_path'] = $jalurGambar;
        }

        CompanyAsset::updateOrCreate(['id' => $this->idAset], $data);

        $this->dispatch('notify', message: 'Data aset tetap berhasil disimpan.', type: 'success');
        $this->tampilkanForm = false;
    }

    /**
     * Menghitung nilai aset saat ini menggunakan metode garis lurus.
     */
    private function hitungNilaiSekarang($biaya, $tanggal, $tahun)
    {
        $usiaTahun = Carbon::parse($tanggal)->floatDiffInYears(now());
        if ($usiaTahun >= $tahun) {
            return 0;
        }

        $depresiasiPerTahun = $biaya / $tahun;
        $nilai = $biaya - ($depresiasiPerTahun * $usiaTahun);

        return max(0, $nilai);
    }

    /**
     * Membuka formulir edit aset.
     */
    public function ubah($id)
    {
        $aset = CompanyAsset::findOrFail($id);
        $this->idAset = $aset->id;
        $this->nama = $aset->name;
        $this->nomorSeri = $aset->serial_number;
        $this->tanggalBeli = $aset->purchase_date->format('Y-m-d');
        $this->biayaBeli = $aset->purchase_cost;
        $this->umurEkonomisTahun = $aset->useful_life_years;
        $this->kondisi = $aset->condition;
        $this->lokasi = $aset->location;
        
        $this->tampilkanForm = true;
        $this->tampilkanInfoDepresiasi = false;
    }

    /**
     * Menampilkan detail perhitungan penyusutan aset.
     */
    public function tampilkanDepresiasi($id)
    {
        $this->asetTerpilih = CompanyAsset::findOrFail($id);
        $this->kalkulasiJadwalDepresiasi();
        $this->tampilkanInfoDepresiasi = true;
        $this->tampilkanForm = false;
    }

    public function tutupInfoDepresiasi()
    {
        $this->tampilkanInfoDepresiasi = false;
        $this->asetTerpilih = null;
    }

    private function kalkulasiJadwalDepresiasi()
    {
        $biaya = $this->asetTerpilih->purchase_cost;
        $tahun = $this->asetTerpilih->useful_life_years;
        $depresiasiPerTahun = $biaya / $tahun;
        $tahunBeli = $this->asetTerpilih->purchase_date->year;

        $this->jadwalDepresiasi = [];
        $nilaiBuku = $biaya;

        for ($i = 0; $i <= $tahun; $i++) {
            $tahunJalan = $tahunBeli + $i;
            
            // Tahun 0 adalah saat pembelian (belum ada penyusutan akumulasi)
            $bebanTahunIni = $i == 0 ? 0 : $depresiasiPerTahun;
            
            // Pastikan nilai buku tidak negatif di akhir
            $nilaiBukuAkhir = max(0, $nilaiBuku - $bebanTahunIni);

            $this->jadwalDepresiasi[] = [
                'tahun' => $tahunJalan,
                'nilai_awal' => $nilaiBuku,
                'depresiasi' => $bebanTahunIni,
                'nilai_akhir' => $nilaiBukuAkhir,
            ];

            // Update nilai buku untuk iterasi berikutnya (kecuali tahun 0)
            if ($i > 0) {
                $nilaiBuku = $nilaiBukuAkhir;
            }
        }
    }

    public function hapus($id)
    {
        CompanyAsset::destroy($id);
        $this->dispatch('notify', message: 'Aset telah dihapus dari inventaris.', type: 'success');
        $this->tampilkanInfoDepresiasi = false;
    }

    public function render()
    {
        $daftarAset = CompanyAsset::query()
            ->when($this->cari, function($q) {
                $q->where('name', 'like', '%'.$this->cari.'%')
                  ->orWhere('serial_number', 'like', '%'.$this->cari.'%');
            })
            ->latest()
            ->paginate(10);

        // Update kalkulasi on-the-fly untuk tampilan list
        foreach ($daftarAset as $aset) {
            $aset->current_value = $this->hitungNilaiSekarang($aset->purchase_cost, $aset->purchase_date, $aset->useful_life_years);
        }

        return view('livewire.assets.index', ['daftarAset' => $daftarAset]);
    }
}