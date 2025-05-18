@extends('layouts.master')

@section('title', 'Notifications')

@section('content')
<div class="container mt-4">
    <h1><i class="fas fa-bell me-2"></i>Notifications</h1>

    <div class="card mt-4">
        <div class="card-body">
            @if($notifications->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>You have no notifications.
                </div>
            @else
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">
                                    @if($notification->type === 'App\Notifications\VoucherNotification')
                                        <i class="fas fa-ticket-alt text-primary me-2"></i>
                                        New Voucher Available!
                                    @elseif($notification->type === 'App\Notifications\CreditRequestNotification')
                                        <i class="fas fa-money-bill-wave text-warning me-2"></i>
                                        New Credit Request
                                    @else
                                        <i class="fas fa-bell text-primary me-2"></i>
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    @endif
                                </h5>
                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            
                            @if($notification->type === 'App\Notifications\VoucherNotification')
                                <p class="mb-1">
                                    You have received a new voucher with code: 
                                    <strong>{{ $notification->data['code'] }}</strong>
                                </p>
                                <p class="mb-1">
                                    Discount: {{ $notification->data['discount_percentage'] }}%
                                </p>
                                <p class="mb-1">
                                    Valid until: {{ $notification->data['expires_at'] }}
                                </p>
                                <a href="{{ route('vouchers.show', $notification->data['voucher_id']) }}" 
                                   class="btn btn-sm btn-primary mt-2">
                                    View Voucher
                                </a>
                            @elseif($notification->type === 'App\Notifications\CreditRequestNotification')
                                <p class="mb-1">
                                    {{ $notification->data['message'] }}
                                </p>
                                <p class="mb-1">
                                    Amount: ${{ number_format($notification->data['amount'], 2) }}
                                </p>
                                <a href="{{ route('cards.credit-requests') }}" 
                                   class="btn btn-sm btn-warning mt-2">
                                    View Request
                                </a>
                            @else
                                <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 