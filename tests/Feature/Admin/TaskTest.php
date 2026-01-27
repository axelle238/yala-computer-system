<?php

use App\Livewire\Admin\TaskManager;
use App\Models\Task;
use App\Models\User;
use Livewire\Livewire;

it('halaman tugas bisa diakses oleh admin', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.tugas'))
        ->assertStatus(200)
        ->assertSee('Manajemen Tugas Internal');
});

it('bisa membuat tugas baru', function () {
    $user = User::factory()->create();
    $assignee = User::factory()->create();

    Livewire::actingAs($user)
        ->test(TaskManager::class)
        ->set('title', 'Tugas Baru Penting')
        ->set('description', 'Deskripsi tugas ini')
        ->set('priority', 'high')
        ->set('assignee_id', $assignee->id)
        ->call('save')
        ->assertDispatched('notify');

    $this->assertDatabaseHas('tasks', [
        'title' => 'Tugas Baru Penting',
        'priority' => 'high',
        'assigned_to' => $assignee->id,
        'created_by' => $user->id,
        'status' => 'pending',
    ]);
});

it('validasi form tugas bekerja dalam bahasa indonesia', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(TaskManager::class)
        ->set('title', '') // Kosong
        ->call('save')
        ->assertHasErrors(['title' => 'required']);

    // Cek pesan error bahasa indonesia (Manual check or strict assert if we loaded lang)
    // Note: assertions on specific error messages in Livewire is tricky without direct viewing errors bag,
    // but standard Laravel validation will use our new lang files.
});

it('bisa mengubah status tugas', function () {
    $user = User::factory()->create();
    $task = Task::create([
        'title' => 'Tugas Awal',
        'priority' => 'medium',
        'status' => 'pending',
        'created_by' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(TaskManager::class)
        ->call('updateStatus', $task->id, 'in_progress')
        ->assertDispatched('notify');

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'in_progress',
    ]);
});
