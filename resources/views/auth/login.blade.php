@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-4 text-center">เข้าสู่ระบบด้วย Google</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @error('google')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <a href="{{ route('auth.google') }}" class="btn btn-outline-dark w-100">
                    Continue with Google
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
