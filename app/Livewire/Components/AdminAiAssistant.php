<?php

namespace App\Livewire\Components;

use App\Services\YalaIntelligence;
use Livewire\Component;

class AdminAiAssistant extends Component
{
    public $isOpen = false;
    public $inputQuery = '';
    public $chatHistory = [];
    public $isThinking = false;

    public function mount()
    {
        // Pesan pembuka
        $this->chatHistory[] = [
            'role' => 'ai',
            'message' => 'Halo Admin! 👋 Saya Yala Brain, asisten cerdas Anda. Ada yang bisa saya bantu analisis hari ini? (Misal: "Cek omset bulan ini" atau "Prediksi stok")',
            'time' => now()->format('H:i')
        ];
    }

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function ask()
    {
        if (empty(trim($this->inputQuery))) return;

        // 1. Simpan pertanyaan user
        $userQ = $this->inputQuery;
        $this->chatHistory[] = [
            'role' => 'user',
            'message' => $userQ,
            'time' => now()->format('H:i')
        ];
        $this->inputQuery = '';
        $this->isThinking = true;

        // 2. Simulasi berpikir (UX)
        // Di Livewire real, kita bisa pakai defer atau sleep sebentar
    }

    public function processAnswer(YalaIntelligence $ai)
    {
        if (!$this->isThinking) return;

        // Ambil pertanyaan terakhir
        $lastUserChat = collect($this->chatHistory)->last(fn($i) => $i['role'] == 'user');
        
        if ($lastUserChat) {
            $answer = $ai->tanyaData($lastUserChat['message']);
            
            $this->chatHistory[] = [
                'role' => 'ai',
                'message' => $answer,
                'time' => now()->format('H:i')
            ];
        }
        
        $this->isThinking = false;
    }

    public function render()
    {
        return view('livewire.components.admin-ai-assistant');
    }
}
