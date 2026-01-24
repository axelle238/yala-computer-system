<?php

namespace App\Livewire\Admin;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Task Manager - Yala Computer')]
class TaskManager extends Component
{
    public $search = '';
    public $showModal = false;
    
    // Task Model
    public $taskId;
    public $title;
    public $description;
    public $priority = 'medium'; // low, medium, high
    public $assigned_to;
    public $due_date;
    public $status = 'todo';

    public function create()
    {
        $this->reset(['taskId', 'title', 'description', 'priority', 'assigned_to', 'due_date']);
        $this->status = 'todo';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->priority = $task->priority;
        $this->assigned_to = $task->assigned_to;
        $this->due_date = $task->due_date?->format('Y-m-d');
        $this->status = $task->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,completed',
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'assigned_by' => Auth::id(), // Only on create? Or update? Keep simple.
            'priority' => $this->priority,
            'status' => $this->status,
            'due_date' => $this->due_date,
        ];

        if ($this->taskId) {
            Task::find($this->taskId)->update($data);
            $message = 'Task updated successfully.';
        } else {
            Task::create($data);
            $message = 'Task created successfully.';
        }

        $this->dispatch('notify', message: $message, type: 'success');
        $this->showModal = false;
    }

    public function updateStatus($id, $newStatus)
    {
        Task::find($id)->update(['status' => $newStatus]);
    }

    public function delete($id)
    {
        Task::find($id)->delete();
        $this->dispatch('notify', message: 'Task deleted.', type: 'success');
    }

    public function render()
    {
        $users = User::whereIn('role', ['admin', 'technician', 'warehouse', 'cashier'])->get(); // Staff only

        // Kanban logic: Get all tasks
        $tasks = Task::with(['assignee', 'creator'])
            ->when($this->search, function($q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            })
            ->orderBy('due_date')
            ->get();

        return view('livewire.admin.task-manager', [
            'users' => $users,
            'todoTasks' => $tasks->where('status', 'todo'),
            'progressTasks' => $tasks->where('status', 'in_progress'),
            'completedTasks' => $tasks->where('status', 'completed'),
        ]);
    }
}
