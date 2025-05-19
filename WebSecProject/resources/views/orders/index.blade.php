@extends('layouts.master')
@section('title', 'Order History')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order History</h5>
                    @if($orders->where('checkout', false)->count() > 0)
                        <a href="{{ route('orders.checkout') }}" class="btn btn-primary">
                            Checkout ({{ $orders->where('checkout', false)->count() }} items)
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    @if($orders->isEmpty())
                        <p class="text-center">No orders found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->product->name }}</td>
                                            <td>{{ $order->quantity }}</td>
                                            <td>${{ number_format($order->price_at_purchase, 2) }}</td>
                                            <td>${{ number_format($order->price_at_purchase * $order->quantity, 2) }}</td>
                                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                @if($order->checkout)
                                                    <span class="badge bg-success">Completed</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection