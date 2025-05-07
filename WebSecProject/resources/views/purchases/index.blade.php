@extends('layouts.master')
@section('title', 'Purchase History')
@section('content')
<div class="container mt-4">
    <h1><i class="fas fa-history me-2"></i>Purchase History</h1>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($purchases->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>You haven't made any purchases yet.
        </div>
    @else
        <div class="card mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Card</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->created_at->format('M d, Y H:i') }}</td>
                                    <td>**** **** **** {{ substr($purchase->card->card_number, -4) }}</td>
                                    <td>{{ $purchase->items->sum('quantity') }} items</td>
                                    <td>
                                        @if($purchase->voucher_discount > 0)
                                            <span class="text-decoration-line-through text-muted">
                                                ${{ number_format($purchase->total_amount, 2) }}
                                            </span>
                                            <br>
                                            <span class="text-success">
                                                ${{ number_format($purchase->final_amount, 2) }}
                                            </span>
                                        @else
                                            ${{ number_format($purchase->final_amount, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ ucfirst($purchase->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i>Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 