<?php

namespace App\Livewire\Member;

use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Buku Alamat Saya')]
class Addresses extends Component
{
    public $addresses;
    public $showForm = false;
    public $addressId;
    
    // Form
    public $label = 'Rumah';
    public $recipient_name;
    public $phone_number;
    public $address_line;
    public $city;
    public $is_primary = false;

    // Static Cities (Should be from RajaOngkir/API in real app)
    public $cities = [
        'Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan', 'Denpasar', 'Makassar'
    ];

    public function mount()
    {
        $this->refreshAddresses();
    }

    public function refreshAddresses()
    {
        $this->addresses = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->get();
    }

    public function create()
    {
        $this->reset(['addressId', 'label', 'recipient_name', 'phone_number', 'address_line', 'city', 'is_primary']);
        $this->recipient_name = Auth::user()->name;
        $this->phone_number = Auth::user()->phone;
        $this->showForm = true;
    }

    public function edit($id)
    {
        $addr = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        $this->addressId = $addr->id;
        $this->label = $addr->label;
        $this->recipient_name = $addr->recipient_name;
        $this->phone_number = $addr->phone_number;
        $this->address_line = $addr->address_line;
        $this->city = $addr->city;
        $this->is_primary = $addr->is_primary;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'label' => 'required',
            'recipient_name' => 'required',
            'phone_number' => 'required',
            'address_line' => 'required',
            'city' => 'required',
        ]);

        if ($this->is_primary) {
            // Unset other primary
            UserAddress::where('user_id', Auth::id())->update(['is_primary' => false]);
        }

        UserAddress::updateOrCreate(['id' => $this->addressId], [
            'user_id' => Auth::id(),
            'label' => $this->label,
            'recipient_name' => $this->recipient_name,
            'phone_number' => $this->phone_number,
            'address_line' => $this->address_line,
            'city' => $this->city,
            'is_primary' => $this->is_primary,
        ]);

        $this->showForm = false;
        $this->refreshAddresses();
        $this->dispatch('notify', message: 'Alamat berhasil disimpan.', type: 'success');
    }

    public function delete($id)
    {
        UserAddress::where('user_id', Auth::id())->where('id', $id)->delete();
        $this->refreshAddresses();
        $this->dispatch('notify', message: 'Alamat dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.member.addresses');
    }
}