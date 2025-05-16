@extends('layouts.master')
@section('title', 'Purchase Details')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-receipt me-2"></i>Purchase Details</h1>
        <a href="{{ route('purchases.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to History
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Items</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
                    <div class="mb-3">
                        <strong>Order Date:</strong><br>
                        {{ $purchase->created_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>Payment Method:</strong><br>
                        Card ending in
                        @if($purchase->card)
                            {{ substr($purchase->card->card_number, -4) }}
                        @else
                            Paid with Credits
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge bg-success">{{ ucfirst($purchase->status) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($purchase->total_amount, 2) }}</span>
                    </div>
                    @if($purchase->voucher_discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Voucher Discount:</span>
                            <span>-${{ number_format($purchase->voucher_discount, 2) }}</span>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>${{ number_format($purchase->final_amount, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 