<?php

use App\Livewire\Rma\Manager;
use App\Models\CashRegister;
use App\Models\Order;
use App\Models\Rma;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;

uses(DatabaseTransactions::class);

it('halaman rma manager bisa diakses admin', function () {
    $admin = User::factory()->create();
    
    $this->actingAs($admin)
        ->get(route('rma.index'))
        ->assertStatus(200)
        ->assertSee('RMA');
});

it('admin bisa memproses refund rma dengan sesi kasir aktif', function () {
    $admin = User::factory()->create();
    $customer = User::factory()->create();
    
    // 1. Setup Cash Register (OPEN)
    $register = CashRegister::create([
        'user_id' => $admin->id,
        'opened_at' => now(),
        'opening_cash' => 1000000,
        'status' => 'open',
    ]);

    // 2. Setup RMA
    $order = Order::create([
        'user_id' => $customer->id,
        'order_number' => 'ORD-TEST-REFUND',
        'status' => 'completed',
        'total_amount' => 500000
    ]);

    $rma = Rma::create([
        'user_id' => $customer->id,
        'order_id' => $order->id,
        'rma_number' => 'RMA-TEST-REFUND',
        'reason' => 'Rusak',
        'status' => 'received', 
    ]);

    // 3. Eksekusi Livewire
    Livewire::actingAs($admin)
        ->test(Manager::class)
        ->call('openDetail', $rma->id)
        ->set('resolutionAction', 'refund')
        ->set('refundAmount', 150000)
        ->set('adminNotes', 'Refund disetujui sebagian.')
        ->call('resolveRma')
        ->assertDispatched('notify', type: 'success'); // Harus sukses

    // 4. Assertions
    $this->assertDatabaseHas('rmas', [
        'id' => $rma->id,
        'status' => 'resolved',
        'resolution_type' => 'refund',
        'refund_amount' => 150000,
    ]);

    $this->assertDatabaseHas('cash_transactions', [
        'cash_register_id' => $register->id,
        'type' => 'out',
        'category' => 'refund',
        'amount' => 150000,
        'reference_id' => $rma->id,
        'reference_type' => Rma::class,
    ]);
});

it('admin gagal refund jika tidak ada sesi kasir aktif', function () {
    $admin = User::factory()->create();
    $customer = User::factory()->create();

    $order = Order::create([
        'user_id' => $customer->id,
        'order_number' => 'ORD-FAIL-REFUND',
        'status' => 'completed',
        'total_amount' => 500000
    ]);

    $rma = Rma::create([
        'user_id' => $customer->id,
        'order_id' => $order->id,
        'rma_number' => 'RMA-FAIL-REFUND',
        'reason' => 'Rusak',
        'status' => 'received',
    ]);

    Livewire::actingAs($admin)
        ->test(Manager::class)
        ->call('openDetail', $rma->id)
        ->set('resolutionAction', 'refund')
        ->set('refundAmount', 50000)
        ->set('adminNotes', 'Coba refund tanpa kasir.')
        ->call('resolveRma')
        ->assertDispatched('notify'); // Harapkan notifikasi error

    // Status tidak berubah
    $this->assertDatabaseHas('rmas', [
        'id' => $rma->id,
        'status' => 'received',
    ]);
});
