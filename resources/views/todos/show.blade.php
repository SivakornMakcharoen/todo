@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1>{{ $todo->title }}</h1>

            <div class="mb-3">
                <span class="badge bg-{{ $todo->status === 'completed' ? 'success' : 'warning' }} text-dark">{{ ucfirst($todo->status) }}</span>
                <span class="text-muted ms-2">Due: {{ $todo->due_date?->format('Y-m-d') ?? 'No deadline' }}</span>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Description</h5>
                    <p class="card-text">{{ $todo->description ?: 'No description added.' }}</p>
                </div>
            </div>

            <div class="mb-3">
                <strong>Created:</strong> {{ $todo->created_at->format('Y-m-d H:i') }}<br>
                <strong>Updated:</strong> {{ $todo->updated_at->format('Y-m-d H:i') }}
            </div>

            <a href="{{ route('todos.edit', $todo) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('todos.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection
