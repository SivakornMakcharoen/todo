@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Todos</h1>
    <div>
        <a href="{{ route('todos.index', ['status' => null]) }}" class="btn btn-outline-secondary btn-sm">All</a>
        <a href="{{ route('todos.index', ['status' => 'pending']) }}" class="btn btn-outline-warning btn-sm">Pending</a>
        <a href="{{ route('todos.index', ['status' => 'completed']) }}" class="btn btn-outline-success btn-sm">Completed</a>
    </div>
</div>

@if($todos->isEmpty())
    <div class="alert alert-info">No todos yet. Add one to get started.</div>
@else
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($todos as $todo)
                    <tr>
                        <td><a href="{{ route('todos.show', $todo) }}">{{ $todo->title }}</a></td>
                        <td>
                            <span class="badge bg-{{ $todo->status === 'completed' ? 'success' : 'warning' }} text-dark">{{ ucfirst($todo->status) }}</span>
                        </td>
                        <td>{{ $todo->due_date?->format('Y-m-d') ?? '-' }}</td>
                        <td>{{ $todo->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('todos.edit', $todo) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this todo?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
