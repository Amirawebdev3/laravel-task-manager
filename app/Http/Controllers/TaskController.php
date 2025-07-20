<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of tasks
     */
    public function index()
    {
        $tasks = $this->taskService->getUserTasks(auth()->id());
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
        ]);
        // Add authenticated user's ID
        $validated['user_id'] = auth()->id();

        $this->taskService->createTask($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit($id)
    {
        $task = $this->taskService->getTask($id);
        
        // Authorization check
        if (auth()->id() !== $task->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'completed' => 'sometimes|boolean'
        ]);

        $task = $this->taskService->updateTask($id, $validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task
     */
    public function destroy($id)
    {
        $this->taskService->deleteTask($id);
        
        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Mark task as complete
     */
    public function complete($id)
    {
        $this->taskService->completeTask($id);
        
        return redirect()->route('tasks.index')
            ->with('success', 'Task marked as complete.');
    }
}