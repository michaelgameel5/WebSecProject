@extends('layouts.master')
@section('title', 'Profile')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Your Profile</h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title">Personal Information</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-id-card me-2"></i>ID</span>
                                <span class="text-muted">{{ $user->id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user me-2"></i>Name</span>
                                <span class="text-muted">{{ $user->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-envelope me-2"></i>Email</span>
                                <span class="text-muted">{{ $user->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user-tag me-2"></i>Role(s)</span>
                                <span class="badge bg-primary rounded-pill">
                                    {{ implode(', ', $user->getRoleNames()->toArray()) }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h5 class="card-title">Account Information</h5>
                        <ul class="list-group list-group-flush">
                            @if($user->hasRole('customer'))
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-wallet me-2"></i>Credit Balance</span>
                                <span class="text-muted">${{ number_format($user->credit, 2) }}</span>
                            </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar-alt me-2"></i>Member Since</span>
                                <span class="text-muted">{{ $user->created_at->format('M d, Y') }}</span>
                            </li>
                        </ul>
                    </div>

                    @if($user->is_employee)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            As an employee, you have access to additional features like managing user credits and products.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection