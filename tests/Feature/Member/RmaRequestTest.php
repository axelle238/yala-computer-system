<?php

use App\Livewire\Member\RmaRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rma;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(DatabaseTransactions::class);

it('halaman rma request bisa diakses oleh member', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('anggota.garansi.ajukan'))
        ->assertStatus(200)
        ->assertSee('Klaim Garansi (RMA)');
});

it('bisa membuat permintaan rma dengan upload bukti', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $product = Product::factory()->create();

    // Create eligible order (completed, < 1 year)
    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'ORD-123',
        'status' => 'completed',
        'total_amount' => 100000,
        'created_at' => now()->subMonth(),
    ]);

    $orderItem = OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => 100000,
        'total' => 100000,
    ]);

    $file = UploadedFile::fake()->image('bukti.jpg');

    Livewire::actingAs($user)
        ->test(RmaRequest::class)
        ->set('selectedOrderId', $order->id)
        ->set('selectedItems.'.$orderItem->id.'.selected', true)
        ->set('selectedItems.'.$orderItem->id.'.reason', 'Mati Total')
        ->set('selectedItems.'.$orderItem->id.'.condition', 'Lengkap')
        ->set('description', 'Tiba-tiba tidak menyala saat dinyalakan.')
        ->set('evidencePhotos', [$file])
        ->call('submitRequest')
        ->assertRedirect(route('anggota.beranda'));

    // Assert Database
    $this->assertDatabaseHas('rmas', [
        'user_id' => $user->id,
        'order_id' => $order->id,
        'reason' => 'Tiba-tiba tidak menyala saat dinyalakan.',
        'status' => 'requested',
    ]);

    // Assert Item & File
    $rma = Rma::where('user_id', $user->id)->first();
    $this->assertDatabaseHas('rma_items', [
        'rma_id' => $rma->id,
        'product_id' => $product->id,
        'problem_description' => 'Mati Total',
    ]);

    // Check evidence path
    $rmaItem = $rma->item->first();
    expect($rmaItem->evidence_files)->not->toBeNull();
    $files = json_decode($rmaItem->evidence_files);
    expect(count($files))->toBe(1);

    // Assert Storage
    Storage::disk('public')->assertExists($files[0]);
});

it('validasi gagal jika tidak memilih item', function () {
    $user = User::factory()->create();
    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'ORD-FAIL',
        'status' => 'completed',
        'total_amount' => 100000,
    ]);

    Livewire::actingAs($user)
        ->test(RmaRequest::class)
        ->set('selectedOrderId', $order->id)
        ->set('description', 'Deskripsi valid tapi item kosong')
        ->call('submitRequest')
        ->assertHasErrors(['selectedItems']); // Livewire standard validation
});
