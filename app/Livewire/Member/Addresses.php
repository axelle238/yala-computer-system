<?php

namespace App\Livewire\Member;

use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Buku Alamat - Member Area')]
class Addresses extends Component
{
    public $addresses;
    public $showForm = false;
    public $editId = null;

    // Form Fields
    public $label = 'Rumah';
    public $recipient_name;
    public $phone_number;
    public $address_line;
    public $city;
    public $postal_code;
    public $is_primary = false;

    // Mock Cities (Same as Checkout)
    public $cities = [
        'Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi', 
        'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan', 
        'Denpasar', 'Makassar'
    ];

    public function mount()
    {
        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        $this->addresses = UserAddress::where('user_id', Auth::id())->latest()->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        $this->editId = $id;
        $this->label = $address->label;
        $this->recipient_name = $address->recipient_name;
        $this->phone_number = $address->phone_number;
        $this->address_line = $address->address_line;
        $this->city = $address->city;
        $this->postal_code = $address->postal_code;
        $this->is_primary = $address->is_primary;
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->editId = null;
        $this->label = 'Rumah';
        $this->recipient_name = Auth::user()->name;
        $this->phone_number = Auth::user()->phone ?? '';
        $this->address_line = '';
        $this->city = '';
        $this->postal_code = '';
        $this->is_primary = false;
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
            // Unset other primaries
            UserAddress::where('user_id', Auth::id())->update(['is_primary' => false]);
        }

        $data = [
            'user_id' => Auth::id(),
            'label' => $this->label,
            'recipient_name' => $this->recipient_name,
            'phone_number' => $this->phone_number,
            'address_line' => $this->address_line,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'is_primary' => $this->is_primary,
        ];

        if ($this->editId) {
            UserAddress::where('id', $this->editId)->where('user_id', Auth::id())->update($data);
            $message = 'Alamat diperbarui.';
        } else {
            // If first address, make primary automatically
            if ($this->addresses->isEmpty()) {
                $data['is_primary'] = true;
            }
            UserAddress::create($data);
            $message = 'Alamat baru ditambahkan.';
        }

        $this->showForm = false;
        $this->loadAddresses();
        $this->dispatch('notify', message: $message, type: 'success');
    }

    public function delete($id)
    {
        UserAddress::where('id', $id)->where('user_id', Auth::id())->delete();
        $this->loadAddresses();
        $this->dispatch('notify', message: 'Alamat dihapus.');
    }

    public function render()
    {
        return view('livewire.member.addresses');
    }
}
