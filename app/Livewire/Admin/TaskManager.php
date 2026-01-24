<?php

namespace App\Livewire\Admin;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Tugas & Kolaborasi - Yala Computer')]
class TaskManager extends Component
{
    use WithPagination;

    public $filterStatus = 'all'; // all, pending, in_progress, completed
    public $filterMyTasks = false; // Toggle to see only my tasks

    // Create/Edit Modal
    public $showModal = false;
    public $isEditMode = false;
    public $taskId;
    
    public $title;
    public $description;
    public $assigned_to;
    public $priority = 'medium';
    public $due_date;
    public $status = 'pending';

    public function updatedFilterStatus() { $this->resetPage(); }
    public function updatedFilterMyTasks() { $this->resetPage(); }

    public function create()
    {
        $this->reset(['title', 'description', 'assigned_to', 'priority', 'due_date', 'status', 'taskId', 'isEditMode']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $this->taskId = $id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->assigned_to = $task->assigned_to;
        $this->priority = $task->priority;
        $this->due_date = $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : null;
        $this->status = $task->status;
        
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
            'status' => $this->status,
        ];

        if ($this->isEditMode) {
            $task = Task::findOrFail($this->taskId);
            $task->update($data);
            
            // Notify assignee if changed
            if ($task->assigned_to && $task->assigned_to != Auth::id()) {
                // Implementation of notification logic would go here
            }
            
            $this->dispatch('notify', message: 'Tugas diperbarui.', type: 'success');
        } else {
            $data['created_by'] = Auth::id();
            Task::create($data);
            $this->dispatch('notify', message: 'Tugas baru dibuat.', type: 'success');
        }

        $this->showModal = false;
    }

    public function delete($id)
    {
        Task::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Tugas dihapus.', type: 'success');
    }

    public function updateStatus($id, $newStatus)
    {
        $task = Task::findOrFail($id);
        $task->update(['status' => $newStatus]);
        $this->dispatch('notify', message: 'Status tugas diperbarui.', type: 'success');
    }

    public function render()
    {
        $tasks = Task::with(['creator', 'assignee'])
            ->when($this->filterStatus !== 'all', fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterMyTasks, function($q) {
                $q->where('assigned_to', Auth::id());
            })
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('due_date', 'asc')
            ->paginate(9);

        return view('livewire.admin.task-manager', [
            'tasks' => $tasks,
            'users' => User::where('is_active', true)->get()
        ]);
    }
}
