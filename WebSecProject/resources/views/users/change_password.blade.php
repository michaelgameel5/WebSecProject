@extends('layouts.master')
@section('title', 'Change Password')
@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Change Password for {{ $user->name }}</h1>
    <form action="{{ route('users.change_password', $user) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        <div class="d-flex justify-content-between">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </div>
    </form>
</div>
@endsection 