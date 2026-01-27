<?php

namespace App\Livewire\Employees;

use App\Models\Attendance;
use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\EmployeeDetail;
use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\ServiceTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Pengelola Penggajian Karyawan - Yala Computer')]
class PayrollManager extends Component
{
    use WithPagination;

    // Status Tampilan
    public $aksiAktif = null; // null, 'buat', 'detail'

    // Input Pembuatan
    public $idUserTerpilih;

    public $bulanTerpilih; // Format: Y-m

    // Data Pratinjau
    public $dataPratinjau = null;

    // Data Detail
    public $penggajianTerpilih;

    /**
     * Inisialisasi komponen.
     */
    public function mount()
    {
        $this->bulanTerpilih = date('Y-m');
    }

    /**
     * Membuka panel pembuatan gaji.
     */
    public function bukaPanelBuat()
    {
        $this->reset(['idUserTerpilih', 'dataPratinjau']);
        $this->aksiAktif = 'buat';
    }

    /**
     * Menutup semua panel detail/buat.
     */
    public function tutupPanel()
    {
        $this->aksiAktif = null;
        $this->penggajianTerpilih = null;
        $this->dataPratinjau = null;
    }

    /**
     * Menghitung pratinjau gaji berdasarkan data absensi dan performa.
     */
    public function hitungPratinjau()
    {
        $this->validate([
            'idUserTerpilih' => 'required|exists:users,id',
            'bulanTerpilih' => 'required',
        ], [
            'idUserTerpilih.required' => 'Silakan pilih karyawan.',
            'bulanTerpilih.required' => 'Pilih periode bulan.',
        ]);

        $pegawai = EmployeeDetail::where('user_id', $this->idUserTerpilih)->first();
        if (! $pegawai) {
            $this->addError('idUserTerpilih', 'Data detail HR untuk karyawan ini belum diatur.');

            return;
        }

        $awal = Carbon::parse($this->bulanTerpilih)->startOfMonth();
        $akhir = Carbon::parse($this->bulanTerpilih)->endOfMonth();

        // 1. Gaji Pokok
        $gajiPokok = $pegawai->base_salary;

        // 2. Tunjangan Kehadiran (Harian)
        $jumlahHadir = Attendance::where('user_id', $this->idUserTerpilih)
            ->whereBetween('date', [$awal, $akhir])
            ->whereIn('status', ['present', 'late'])
            ->count();
        $totalTunjangan = $jumlahHadir * $pegawai->allowance_daily;

        // 3. Komisi Servis (Berdasarkan Unit Selesai)
        $tiketSelesai = ServiceTicket::where('technician_id', $this->idUserTerpilih)
            ->where('status', 'picked_up')
            ->whereBetween('updated_at', [$awal, $akhir])
            ->get();

        $jumlahKomisi = 0;
        if ($pegawai->commission_percentage > 0) {
            foreach ($tiketSelesai as $tiket) {
                $jumlahKomisi += ($tiket->final_cost * ($pegawai->commission_percentage / 100));
            }
        }

        // 4. Bonus Performa (Kompleksitas Baru)
        $bonusPerforma = 0;
        if ($tiketSelesai->count() >= 15) {
            $bonusPerforma = 250000; // Bonus jika menangani > 15 unit
        }

        // 5. Potongan Keterlambatan
        $menitTelat = Attendance::where('user_id', $this->idUserTerpilih)
            ->whereBetween('date', [$awal, $akhir])
            ->sum('late_minutes');
        $potonganTelat = floor($menitTelat / 15) * 10000; // Rp 10.000 per 15 menit telat

        // 6. Pajak Penghasilan (PPh 21 Sederhana - 5% di atas 5jt)
        $totalKotor = $gajiPokok + $totalTunjangan + $jumlahKomisi + $bonusPerforma - $potonganTelat;
        $potonganPajak = $totalKotor > 5000000 ? ($totalKotor * 0.05) : 0;

        $gajiBersih = $totalKotor - $potonganPajak;

        $this->dataPratinjau = [
            'id_user' => $this->idUserTerpilih,
            'nama_user' => $pegawai->user->name,
            'jabatan' => $pegawai->job_title,
            'periode' => $awal->translatedFormat('F Y'),
            'gaji_pokok' => $gajiPokok,
            'jumlah_hadir' => $jumlahHadir,
            'tunjangan_harian' => $pegawai->allowance_daily,
            'total_tunjangan' => $totalTunjangan,
            'jumlah_tiket' => $tiketSelesai->count(),
            'total_komisi' => $jumlahKomisi,
            'bonus_performa' => $bonusPerforma,
            'menit_telat' => $menitTelat,
            'total_potongan' => $potonganTelat,
            'potongan_pajak' => $potonganPajak,
            'gaji_bersih' => $gajiBersih,
        ];
    }

    /**
     * Menyimpan slip gaji ke database.
     */
    public function simpanGaji()
    {
        if (! $this->dataPratinjau) {
            return;
        }

        DB::transaction(function () {
            $penggajian = Payroll::create([
                'user_id' => $this->dataPratinjau['id_user'],
                'payroll_number' => 'GAJI/'.date('ym').'/'.$this->dataPratinjau['id_user'].'/'.rand(100, 999),
                'period_start' => Carbon::parse($this->bulanTerpilih)->startOfMonth(),
                'period_end' => Carbon::parse($this->bulanTerpilih)->endOfMonth(),
                'total_income' => $this->dataPratinjau['gaji_pokok'] + $this->dataPratinjau['total_tunjangan'] + $this->dataPratinjau['total_komisi'] + $this->dataPratinjau['bonus_performa'],
                'total_deduction' => $this->dataPratinjau['total_potongan'] + $this->dataPratinjau['potongan_pajak'],
                'net_salary' => $this->dataPratinjau['gaji_bersih'],
                'status' => 'draft',
                'details' => $this->dataPratinjau,
            ]);

            // Item-item rincian
            $rincian = [
                ['judul' => 'Gaji Pokok', 'tipe' => 'income', 'jumlah' => $this->dataPratinjau['gaji_pokok']],
                ['judul' => "Tunjangan Hadir ({$this->dataPratinjau['jumlah_hadir']} hari)", 'tipe' => 'income', 'jumlah' => $this->dataPratinjau['total_tunjangan']],
            ];

            if ($this->dataPratinjau['total_komisi'] > 0) {
                $rincian[] = ['judul' => 'Komisi Servis', 'tipe' => 'income', 'jumlah' => $this->dataPratinjau['total_komisi']];
            }
            if ($this->dataPratinjau['bonus_performa'] > 0) {
                $rincian[] = ['judul' => 'Bonus Performa', 'tipe' => 'income', 'jumlah' => $this->dataPratinjau['bonus_performa']];
            }
            if ($this->dataPratinjau['total_potongan'] > 0) {
                $rincian[] = ['judul' => 'Potongan Keterlambatan', 'tipe' => 'deduction', 'jumlah' => $this->dataPratinjau['total_potongan']];
            }
            if ($this->dataPratinjau['potongan_pajak'] > 0) {
                $rincian[] = ['judul' => 'Pajak PPh 21', 'tipe' => 'deduction', 'jumlah' => $this->dataPratinjau['potongan_pajak']];
            }

            foreach ($rincian as $item) {
                PayrollItem::create([
                    'payroll_id' => $penggajian->id,
                    'title' => $item['judul'],
                    'type' => $item['tipe'],
                    'amount' => $item['jumlah'],
                ]);
            }
        });

        $this->dispatch('notify', message: 'Slip gaji berhasil diterbitkan dalam status Draft.', type: 'success');
        $this->tutupPanel();
    }

    /**
     * Melihat detail slip gaji.
     */
    public function lihatDetail($id)
    {
        $this->penggajianTerpilih = Payroll::with(['user', 'user.employeeDetail', 'items'])->findOrFail($id);
        $this->aksiAktif = 'detail';
    }

    /**
     * Menyetujui dan membayarkan gaji via kasir.
     */
    public function setujuiDanBayar()
    {
        if (! $this->penggajianTerpilih || $this->penggajianTerpilih->status !== 'draft') {
            return;
        }

        $kasirAktif = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        if (! $kasirAktif) {
            $this->dispatch('notify', message: 'Gagal! Anda harus membuka shift kasir terlebih dahulu untuk mencatat pengeluaran.', type: 'error');

            return;
        }

        if ($kasirAktif->system_balance < $this->penggajianTerpilih->net_salary) {
            $this->dispatch('notify', message: 'Saldo kasir tidak mencukupi untuk melakukan pembayaran gaji.', type: 'error');

            return;
        }

        DB::transaction(function () use ($kasirAktif) {
            $this->penggajianTerpilih->update([
                'status' => 'paid',
                'payment_date' => now(),
                'approved_by' => Auth::id(),
            ]);

            CashTransaction::create([
                'cash_register_id' => $kasirAktif->id,
                'transaction_number' => 'BEBAN-GAJI-'.$this->penggajianTerpilih->id,
                'type' => 'out',
                'category' => 'expense',
                'amount' => $this->penggajianTerpilih->net_salary,
                'description' => "Bayar Gaji: {$this->penggajianTerpilih->user->name} ({$this->penggajianTerpilih->payroll_number})",
                'reference_id' => $this->penggajianTerpilih->id,
                'reference_type' => Payroll::class,
                'created_by' => Auth::id(),
            ]);
        });

        $this->dispatch('notify', message: 'Pembayaran gaji berhasil diproses dan dicatat di laporan kasir.', type: 'success');
        $this->tutupPanel();
    }

    public function render()
    {
        $daftarGaji = Payroll::with('user')
            ->orderBy('period_start', 'desc')
            ->paginate(10);

        $daftarKaryawan = User::whereHas('employeeDetail')->get();

        return view('livewire.employees.payroll-manager', [
            'daftarGaji' => $daftarGaji,
            'daftarKaryawan' => $daftarKaryawan,
        ]);
    }
}
