@extends('layouts.master')
@section('title', 'Forgot Password')
@section('content')
    <form action="{{ route('send_reset_link') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group mb-2">
            <label for="email" class="form-label">Enter your email:</label>
            <input type="email" class="form-control" placeholder="email" name="email" required>
            @error('email')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-2">
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </div>
    </form>
    @if (session('status'))
        <div class="alert alert-success mt-2">
            {{ session('status') }}
        </div>
    @endif
@endsection