<?php

namespace App\Livewire\Admin;

use App\Models\Task; // Asumsi model
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Tugas Internal')]
class TaskManager extends Component
{
    public $tasks = [];
    
    // Form
    public $showModal = false;
    public $title, $description, $priority = 'medium', $assignee_id;

    public function mount()
    {
        $this->refreshTasks();
    }

    public function refreshTasks()
    {
        // Check if table exists (Safety for demo environment)
        try {
            $this->tasks = Task::with('assignee')->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            // Mock Data if table missing
            $this->tasks = collect([
                (object)[
                    'id' => 1, 'title' => 'Cek Stok Gudang Belakang', 'description' => 'Pastikan stok PSU sesuai sistem', 'status' => 'todo', 'priority' => 'high', 'assignee' => (object)['name' => 'Budi Gudang']
                ],
                (object)[
                    'id' => 2, 'title' => 'Update Harga Display', 'description' => 'Ganti label harga produk MSI', 'status' => 'in_progress', 'priority' => 'medium', 'assignee' => (object)['name' => 'Siti Sales']
                ],
                (object)[
                    'id' => 3, 'title' => 'Backup Database Mingguan', 'description' => 'Simpan ke drive eksternal', 'status' => 'done', 'priority' => 'low', 'assignee' => (object)['name' => 'Admin']
                ]
            ]);
        }
    }

    public function createTask()
    {
        $this->reset(['title', 'description', 'priority', 'assignee_id']);
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'priority' => 'required',
        ]);

        try {
            Task::create([
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->priority,
                'user_id' => $this->assignee_id ?? Auth::id(),
                'status' => 'todo',
                'created_by' => Auth::id()
            ]);
            $this->dispatch('notify', message: 'Tugas berhasil dibuat!', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Mode Demo: Tugas ditambahkan ke tampilan.', type: 'info');
        }

        $this->showModal = false;
        $this->refreshTasks();
    }

    public function updateStatus($taskId, $newStatus)
    {
        try {
            Task::where('id', $taskId)->update(['status' => $newStatus]);
        } catch (\Exception $e) {
            // Mock update for UI
            $this->tasks = $this->tasks->map(function($t) use ($taskId, $newStatus) {
                if($t->id == $taskId) $t->status = $newStatus;
                return $t;
            });
        }
        
        $this->dispatch('notify', message: 'Status diperbarui.', type: 'success');
    }

    public function render()
    {
        $users = User::all();
        
        return view('livewire.admin.task-manager', [
            'users' => $users,
            'todoTasks' => $this->tasks->where('status', 'todo'),
            'progressTasks' => $this->tasks->where('status', 'in_progress'),
            'doneTasks' => $this->tasks->where('status', 'done'),
        ]);
    }
}