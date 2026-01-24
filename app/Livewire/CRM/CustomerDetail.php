<?php

namespace App\Livewire\CRM;

use App\Models\Order;
use App\Models\User;
use App\Models\ServiceTicket;
use App\Models\Rma;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Customer 360 View - Yala Computer')]
class CustomerDetail extends Component
{
    public User $customer;
    public $activeTab = 'overview'; // overview, orders, services, rma

    public function mount($id)
    {
        $this->customer = User::withCount(['orders', 'serviceTickets'])->findOrFail($id);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $data = [];
        
        if ($this->activeTab === 'orders') {
            $data['orders'] = Order::where('user_id', $this->customer->id)->latest()->paginate(5);
        } elseif ($this->activeTab === 'services') {
            $data['services'] = ServiceTicket::where('customer_phone', $this->customer->phone)
                ->orWhere('customer_name', $this->customer->name) // Fallback logic
                ->latest()->get();
        } elseif ($this->activeTab === 'rma') {
            $data['rmas'] = Rma::where('user_id', $this->customer->id)->latest()->get();
        }

        return view('livewire.crm.customer-detail', $data);
    }
}