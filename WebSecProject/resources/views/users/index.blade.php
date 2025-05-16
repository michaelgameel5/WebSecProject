@extends('layouts.master')
@section('title', 'Users')
@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Users</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Credits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </td>
                            <td>${{ number_format($user->credit, 2) }}</td>
                            <td>
                                <a href="{{ route('users.add-credit', $user->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus me-1"></i>Add Credit
                                </a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 