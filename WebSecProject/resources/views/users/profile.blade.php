@extends('layouts.master')
@section('title', 'Profile')
@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Your Profile</h2>
    <div class="card">
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>ID:</strong> {{ $user->id }}</li>
                <li class="list-group-item"><strong>Name:</strong> {{ $user->name }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                <li class="list-group-item">
                    <strong>Roles:</strong>
                    <div class="mt-2">
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </div>
                </li>
                @if($user->isCustomer())
                    <li class="list-group-item">
                        <strong><i class="fas fa-credit-card me-2"></i>Credit Card:</strong>
                        <div class="mt-2">
                            <a href="{{ route('cards.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-credit-card me-1"></i>Manage Cards
                            </a>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection