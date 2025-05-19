@extends('layouts.master')
@section('title', 'Manage Users')
@section('content')
<div class="container mt-4">
    <h1 class="mb-4">User Management</h1>
    @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('users.create') }}" class="btn btn-success mb-3">Add Employee / Support Agent / Manager</a>
    @endif
    @if($users->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>No users found.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role(s)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                                @if(auth()->user()->can('change_passwords'))
                                    <a href="{{ route('users.change_password_form', $user) }}" class="btn btn-info btn-sm">Change Password</a>
                                @endif
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </form>
                                @else
                                    <button class="btn btn-danger btn-sm" disabled>Delete</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection 