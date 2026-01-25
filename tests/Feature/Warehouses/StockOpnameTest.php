<?php

use App\Livewire\Warehouses\StockOpname;
use App\Models\Product;
use App\Models\StockOpname as StockOpnameModel;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Buat warehouse default
    Warehouse::factory()->create(['id' => 1, 'name' => 'Gudang Utama']);
});

it('bisa memulai sesi stok opname dan memuat produk', function () {
    $user = User::factory()->create();
    Product::factory()->count(5)->create();

    Livewire::actingAs($user)
        ->test(StockOpname::class)
        ->call('startSession')
        ->assertSet('activeOpname.status', 'counting');

    $this->assertDatabaseCount('stock_opnames', 1);
    $this->assertDatabaseCount('stock_opname_items', 5);
});

it('bisa mengupdate stok fisik per item', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 10]);

    $livewire = Livewire::actingAs($user)->test(StockOpname::class);
    $livewire->call('startSession');

    $opname = StockOpnameModel::first();
    $item = $opname->items()->where('product_id', $product->id)->first();

    $livewire->call('updatePhysicalStock', $item->id, 12);

    $this->assertDatabaseHas('stock_opname_items', [
        'id' => $item->id,
        'physical_stock' => 12,
    ]);
});

it('bisa menyelesaikan opname dan menyesuaikan stok', function () {
    $user = User::factory()->create();
    $productA = Product::factory()->create(['stock_quantity' => 10]);
    $productB = Product::factory()->create(['stock_quantity' => 20]);

    // Mulai sesi
    $livewire = Livewire::actingAs($user)->test(StockOpname::class);
    $livewire->call('startSession');
    $opname = StockOpnameModel::first();

    // Update stok fisik
    $itemA = $opname->items()->where('product_id', $productA->id)->first();
    $itemB = $opname->items()->where('product_id', $productB->id)->first();
    $livewire->call('updatePhysicalStock', $itemA->id, 8); // Selisih -2
    $livewire->call('updatePhysicalStock', $itemB->id, 25); // Selisih +5

    // Selesaikan
    $livewire->call('finalizeOpname');

    // Assert Status
    $this->assertDatabaseHas('stock_opnames', [
        'id' => $opname->id,
        'status' => 'completed',
    ]);

    // Assert Stok Produk
    $this->assertDatabaseHas('products', [
        'id' => $productA->id,
        'stock_quantity' => 8,
    ]);
    $this->assertDatabaseHas('products', [
        'id' => $productB->id,
        'stock_quantity' => 25,
    ]);

    // Assert Log Transaksi
    $this->assertDatabaseHas('inventory_transactions', [
        'product_id' => $productA->id,
        'type' => 'adjustment',
        'quantity' => 2, // absolute variance
        'notes' => 'Stok Opname #'.$opname->opname_number.': Selisih -2',
    ]);
});
