<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $todos = Todo::when($status && in_array($status, ['pending', 'completed']), function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return view('todos.index', compact('todos', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $data['status'] = $request->boolean('checked') ? 'completed' : 'pending';

        Todo::create($data);

        return redirect()->route('todos.index')->with('success', 'Todo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        return view('todos.show', compact('todo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $data['status'] = $request->boolean('checked') ? 'completed' : 'pending';

        $todo->update($data);

        return redirect()->route('todos.index')->with('success', 'Todo updated successfully.');
    }

    public function checklist(Request $request, Todo $todo)
    {
        $todo->update([
            'status' => $request->boolean('checked') ? 'completed' : 'pending',
        ]);

        return back()->with('success', 'Checklist updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()->route('todos.index')->with('success', 'Todo deleted successfully.');
    }
}

