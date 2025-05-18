@extends('layouts.master')
@section('title', 'Checkout')
@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="mb-0">Order Summary</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('products.show', $order->product) }}" class="text-decoration-none">
                                                {{ $order->product->name }}
                                            </a>
                                        </td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>${{ number_format($order->price_at_purchase, 2) }}</td>
                                        <td>${{ number_format($order->price_at_purchase * $order->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Payment Information</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Available Credit</h5>
                        <h2 class="text-{{ $user->credit >= $total ? 'success' : 'danger' }}">
                            ${{ number_format($user->credit, 2) }}
                        </h2>
                    </div>

                    @if($user->credit >= $total)
                        <form action="{{ route('orders.process-checkout') }}" method="POST">
                            @csrf
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                You have sufficient credit to complete this purchase.
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-lock me-2"></i>Complete Purchase
                            </button>
                        </form>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Insufficient credit. You need an additional ${{ number_format($total - $user->credit, 2) }}.
                        </div>
                        <a href="{{ route('credits.show') }}" class="btn btn-warning w-100">
                            <i class="fas fa-wallet me-2"></i>Add Credit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 