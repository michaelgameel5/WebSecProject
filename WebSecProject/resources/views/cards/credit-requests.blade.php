@extends('layouts.master')
@section('title', 'Credit Requests')
@section('content')
<div class="container mt-4">
    <h1><i class="fas fa-money-bill-wave me-2"></i>Credit Requests</h1>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Card</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($creditRequests as $request)
                            <tr>
                                <td>{{ $request->user->name }}</td>
                                <td>
                                    @if($request->card)
                                        **** **** **** {{ substr($request->card->card_number, -4) }}
                                    @else
                                        Paid with Credits
                                    @endif
                                </td>
                                <td>${{ number_format($request->amount, 2) }}</td>
                                <td>
                                    @if($request->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($request->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($request->status === 'pending')
                                        <form action="{{ route('cards.approve-credit', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check me-1"></i>Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('cards.reject-credit', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times me-1"></i>Reject
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">
                                            Processed by: {{ $request->processedBy->name ?? 'N/A' }}<br>
                                            {{ $request->processed_at ? $request->processed_at->format('M d, Y H:i') : '' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No credit requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 