<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\WhatsappBlast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsappBlastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $blast;

    /**
     * Create a new job instance.
     */
    public function __construct(WhatsappBlast $blast)
    {
        $this->blast = $blast;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Memulai proses Whatsapp Blast: {$this->blast->campaign_name}");

        $this->blast->update(['status' => 'processing']);

        // 1. Tentukan Target
        $query = User::whereNotNull('phone')->where('phone', '!=', '');

        if ($this->blast->target_audience === 'loyal') {
            $query->where('total_spent', '>=', 10000000);
        } elseif ($this->blast->target_audience === 'inactive') {
            $query->where('last_purchase_at', '<', now()->subMonths(3));
        }

        // 2. Ambil User (Chunking untuk efisiensi memori)
        $success = 0;
        $failed = 0;

        $query->chunk(100, function ($users) use (&$success, &$failed) {
            foreach ($users as $user) {
                try {
                    // --- SIMULASI INTEGRASI API (Misal: Wablas / Fonnte) ---
                    // $response = Http::post('https://api.whatsapp.service/send', [ ... ]);

                    // Kita simulasikan delay jaringan kecil
                    usleep(100000); // 0.1 detik

                    // Logika dummy: Jika nomor hp valid (panjang > 9), sukses.
                    if (strlen($user->phone) > 9) {
                        $success++;
                        // Log::info("Sent to {$user->phone}");
                    } else {
                        $failed++;
                        Log::warning("Gagal kirim ke {$user->name} (HP: {$user->phone}) - Format salah");
                    }

                } catch (\Exception $e) {
                    $failed++;
                    Log::error("Error sending WA to {$user->id}: ".$e->getMessage());
                }
            }

            // Update progress berkala di database agar admin bisa lihat
            $this->blast->update([
                'success_count' => $success,
                'failed_count' => $failed,
            ]);
        });

        // 3. Selesai
        $this->blast->update([
            'status' => 'completed',
            'success_count' => $success,
            'failed_count' => $failed,
        ]);

        Log::info("Whatsapp Blast Selesai. Sukses: $success, Gagal: $failed");
    }
}
