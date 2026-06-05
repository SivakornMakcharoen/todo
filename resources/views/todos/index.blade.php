@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Check List</h1>
    <div>
        <a href="{{ route('todos.index', ['status' => null]) }}" class="btn btn-outline-secondary btn-sm">All</a>
        <a href="{{ route('todos.index', ['status' => 'pending']) }}" class="btn btn-outline-warning btn-sm">Unchecked</a>
        <a href="{{ route('todos.index', ['status' => 'completed']) }}" class="btn btn-outline-success btn-sm">Checked</a>
    </div>
</div>

@if($todos->isEmpty())
    <div class="alert alert-info">No checklist items yet. Add one to get started.</div>
@else
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 110px;">Check List</th>
                    <th>Title</th>
                    <th>Due Date</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($todos as $todo)
                    <tr>
                        <td>
                            <form action="{{ route('todos.checklist', $todo) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="checked" value="0">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="checked"
                                        value="1"
                                        onchange="this.form.submit()"
                                        {{ $todo->isCompleted() ? 'checked' : '' }}
                                        aria-label="Mark {{ $todo->title }} as checked"
                                    >
                                </div>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('todos.show', $todo) }}" class="{{ $todo->isCompleted() ? 'text-decoration-line-through text-muted' : '' }}">
                                {{ $todo->title }}
                            </a>
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
