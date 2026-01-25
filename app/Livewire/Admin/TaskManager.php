<?php

namespace App\Livewire\Admin;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Manajemen Tugas Internal')]
class TaskManager extends Component
{
    public $tasks = [];

    // View State
    public $activeAction = null; // null, 'create'

    public $title;

    public $description;

    public $priority = 'medium';

    public $assignee_id;

    public function mount()
    {
        $this->refreshTasks();
    }

    public function refreshTasks()
    {
        $this->tasks = Task::with('assignee')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function openCreatePanel()
    {
        $this->reset(['title', 'description', 'priority', 'assignee_id']);
        $this->activeAction = 'create';
    }

    public function closePanel()
    {
        $this->activeAction = null;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'priority' => 'required|in:low,medium,high,urgent',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'assigned_to' => $this->assignee_id ?? Auth::id(),
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        $this->dispatch('notify', message: 'Tugas berhasil dibuat!', type: 'success');

        $this->closePanel();
        $this->refreshTasks();
    }

    public function updateStatus($taskId, $newStatus)
    {
        // Validation for status
        if (! in_array($newStatus, ['pending', 'in_progress', 'completed', 'cancelled'])) {
            return;
        }

        Task::where('id', $taskId)->update(['status' => $newStatus]);

        $this->refreshTasks();
        $this->dispatch('notify', message: 'Status tugas diperbarui.', type: 'success');
    }

    public function render()
    {
        $users = User::all();

        return view('livewire.admin.task-manager', [
            'users' => $users,
            'todoTasks' => $this->tasks->where('status', 'pending'),
            'progressTasks' => $this->tasks->where('status', 'in_progress'),
            'doneTasks' => $this->tasks->where('status', 'completed'),
        ]);
    }
}
