<?php

namespace App\Livewire\Services;

use App\Models\ServiceTicket;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form Servis - Yala Computer')]
class Form extends Component
{
    public ?ServiceTicket $ticket = null;

    public $ticket_number;
    public $customer_name;
    public $customer_phone;
    public $device_name;
    public $problem_description;
    public $status = 'pending';
    public $estimated_cost = 0;
    public $technician_notes;

    public function mount($id = null)
    {
        if ($id) {
            $this->ticket = ServiceTicket::findOrFail($id);
            $this->ticket_number = $this->ticket->ticket_number;
            $this->customer_name = $this->ticket->customer_name;
            $this->customer_phone = $this->ticket->customer_phone;
            $this->device_name = $this->ticket->device_name;
            $this->problem_description = $this->ticket->problem_description;
            $this->status = $this->ticket->status;
            $this->estimated_cost = $this->ticket->estimated_cost;
            $this->technician_notes = $this->ticket->technician_notes;
        } else {
            // Generate Auto Ticket Number: SRV-YYYYMMDD-RAND
            $this->ticket_number = 'SRV-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        }
    }

    public function save()
    {
        $this->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'device_name' => 'required|string',
            'problem_description' => 'required|string',
            'status' => 'required|in:pending,diagnosing,waiting_part,repairing,ready,picked_up,cancelled',
            'estimated_cost' => 'required|numeric|min:0',
        ]);

        $data = [
            'ticket_number' => $this->ticket_number,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'device_name' => $this->device_name,
            'problem_description' => $this->problem_description,
            'status' => $this->status,
            'estimated_cost' => $this->estimated_cost,
            'technician_notes' => $this->technician_notes,
            'technician_id' => auth()->id(), // Last updated by
        ];

        if ($this->ticket) {
            $this->ticket->update($data);
            session()->flash('success', 'Tiket servis diperbarui.');
        } else {
            ServiceTicket::create($data);
            session()->flash('success', 'Tiket servis baru dibuat.');
        }

        return redirect()->route('services.index');
    }

    public function render()
    {
        return view('livewire.services.form');
    }
}
