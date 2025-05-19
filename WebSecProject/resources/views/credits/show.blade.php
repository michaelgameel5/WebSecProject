@extends('layouts.master')
@section('title', 'My Credit')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">My Credit Balance</h2>
                </div>
                <div class="card-body">
                    @if($user->hasRole('employee'))
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            As an employee, you can manage customer credits on the <a href="{{ route('credits.index') }}" class="alert-link">Manage Credits</a> page.
                        </div>
                    @else
                        <div class="text-center mb-4">
                            <h1 class="display-4">${{ number_format($user->credit, 2) }}</h1>
                            <p class="text-muted">Available Credit</p>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Your credit can be used to purchase products. If you need more credit, please contact an employee.
                        </div>
                    @endunless

                    @if($user->is_employee)
                        <div class="text-center mt-4">
                            <a href="{{ route('credits.index') }}" class="btn btn-primary">
                                <i class="fas fa-users me-1"></i>Manage User Credits
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 