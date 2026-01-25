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

    public $cari = '';

    public $tampilkanForm = false;

    public $tampilkanInfoDepresiasi = false; // Ganti modal jadi area info

    // Properti Form
    public $idAset;

    public $nama;

    public $nomorSeri;

    public $tanggalBeli;

    public $biayaBeli;

    public $umurEkonomisTahun;

    public $kondisi = 'good';

    public $lokasi;

    public $gambar;

    // Data Kalkulasi
    public $asetTerpilih;

    public $jadwalDepresiasi = [];

    public function buat()
    {
        $this->reset(['idAset', 'nama', 'nomorSeri', 'tanggalBeli', 'biayaBeli', 'umurEkonomisTahun', 'kondisi', 'lokasi', 'gambar']);
        $this->tampilkanForm = true;
        $this->tampilkanInfoDepresiasi = false;
    }

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

        $path = null;
        if ($this->gambar) {
            $path = $this->gambar->store('assets', 'public');
        }

        $data = [
            'name' => $this->nama,
            'serial_number' => $this->nomorSeri,
            'purchase_date' => $this->tanggalBeli,
            'purchase_cost' => $this->biayaBeli,
            'useful_life_years' => $this->umurEkonomisTahun,
            'condition' => $this->kondisi,
            'location' => $this->lokasi,
            // Hitung nilai saat ini (Metode Garis Lurus)
            'current_value' => $this->hitungNilaiSekarang($this->biayaBeli, $this->tanggalBeli, $this->umurEkonomisTahun),
        ];

        if ($path) {
            $data['image_path'] = $path;
        }

        CompanyAsset::updateOrCreate(['id' => $this->idAset], $data);

        $this->dispatch('notify', message: 'Data aset berhasil disimpan.', type: 'success');
        $this->tampilkanForm = false;
    }

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

    public function tampilkanDepresiasi($id)
    {
        $this->asetTerpilih = CompanyAsset::findOrFail($id);
        $this->buatJadwalDepresiasi();
        $this->tampilkanInfoDepresiasi = true;
        $this->tampilkanForm = false;
    }

    public function tutupInfoDepresiasi()
    {
        $this->tampilkanInfoDepresiasi = false;
        $this->asetTerpilih = null;
    }

    private function buatJadwalDepresiasi()
    {
        $biaya = $this->asetTerpilih->purchase_cost;
        $tahun = $this->asetTerpilih->useful_life_years;
        $depresiasiPerTahun = $biaya / $tahun;
        $tahunBeli = $this->asetTerpilih->purchase_date->year;

        $this->jadwalDepresiasi = [];
        $nilaiSaatIni = $biaya;

        for ($i = 0; $i <= $tahun; $i++) {
            $tahunJalan = $tahunBeli + $i;
            $this->jadwalDepresiasi[] = [
                'tahun' => $tahunJalan,
                'nilai_awal' => $nilaiSaatIni,
                'depresiasi' => $i == 0 ? 0 : $depresiasiPerTahun, // Tahun 0 adalah pembelian
                'nilai_akhir' => $i == 0 ? $biaya : max(0, $nilaiSaatIni - $depresiasiPerTahun),
            ];
            if ($i > 0) {
                $nilaiSaatIni -= $depresiasiPerTahun;
            }
        }
    }

    public function hapus($id)
    {
        CompanyAsset::destroy($id);
        $this->dispatch('notify', message: 'Aset telah dihapus.', type: 'success');
    }

    public function render()
    {
        $daftarAset = CompanyAsset::where('name', 'like', '%'.$this->cari.'%')
            ->latest()
            ->paginate(10);

        // Hitung ulang nilai saat ini secara real-time untuk tampilan
        foreach ($daftarAset as $aset) {
            $aset->current_value = $this->hitungNilaiSekarang($aset->purchase_cost, $aset->purchase_date, $aset->useful_life_years);
        }

        return view('livewire.assets.index', ['daftarAset' => $daftarAset]);
    }
}
