<?php

use App\Models\User;
use App\Livewire\Admin\Inbox;
use App\Models\ContactMessage;
use App\Mail\ContactReplyMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

it('halaman inbox bisa diakses oleh admin', function () {
    $user = User::factory()->create();
    
    $this->actingAs($user)
        ->get(route('customers.inbox'))
        ->assertStatus(200)
        ->assertSee('Inbox');
});

it('bisa memilih dan membaca pesan', function () {
    $user = User::factory()->create();
    $message = ContactMessage::create([
        'name' => 'Budi Customer',
        'email' => 'budi@example.com',
        'subject' => 'Tanya Stok',
        'message' => 'Apakah stok ready?',
        'status' => 'new'
    ]);

    Livewire::actingAs($user)
        ->test(Inbox::class)
        ->call('selectMessage', $message->id)
        ->assertSet('selectedMessage.id', $message->id);
    
    $this->assertDatabaseHas('contact_messages', [
        'id' => $message->id,
        'status' => 'read'
    ]);
});

it('bisa membalas pesan dan mengirim email', function () {
    Mail::fake();
    $user = User::factory()->create();
    $message = ContactMessage::create([
        'name' => 'Siti Customer',
        'email' => 'siti@example.com',
        'subject' => 'Komplain',
        'message' => 'Barang rusak',
        'status' => 'read'
    ]);

    Livewire::actingAs($user)
        ->test(Inbox::class)
        ->call('selectMessage', $message->id)
        ->set('replyBody', 'Mohon maaf, kami akan ganti.')
        ->call('sendReply')
        ->assertDispatched('notify');

    Mail::assertQueued(ContactReplyMail::class, function ($mail) use ($message) {
        return $mail->hasTo($message->email);
    });

    $this->assertDatabaseHas('contact_messages', [
        'id' => $message->id,
        'status' => 'replied'
    ]);
});

it('bisa menghapus pesan', function () {
    $user = User::factory()->create();
    $message = ContactMessage::create([
        'name' => 'Spam',
        'email' => 'spam@example.com',
        'subject' => 'Spam',
        'message' => 'Spam content',
        'status' => 'new'
    ]);

    Livewire::actingAs($user)
        ->test(Inbox::class)
        ->call('delete', $message->id)
        ->assertDispatched('notify');
        
    $this->assertDatabaseMissing('contact_messages', [
        'id' => $message->id
    ]);
});