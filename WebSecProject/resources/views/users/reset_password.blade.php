@extends('layouts.master')
@section('title', 'Reset Password')
@section('content')
    <form action="{{ route('do_reset_password') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group mb-2">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" placeholder="email" name="email" required>
        </div>
        <div class="form-group mb-2">
            <label for="password" class="form-label">New Password:</label>
            <input type="password" class="form-control" placeholder="new password" name="password" required>
        </div>
        <div class="form-group mb-2">
            <label for="password_confirmation" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control" placeholder="confirm password" name="password_confirmation" required>
        </div>
        <div class="form-group mb-2">
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </div>
    </form>
@endsection