@extends('layouts.master')
@section('title', 'Shopping Cart')
@section('content')
<div class="container mt-4">
    <h1><i class="fas fa-shopping-cart me-2"></i>Shopping Cart</h1>

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

    @if($items->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Your cart is empty. <a href="{{ route('products.index') }}" class="alert-link">Continue shopping</a>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @foreach($items as $item)
                            <div class="row mb-4 align-items-center">
                                <div class="col-md-2">
                                    @if($item->product->photo)
                                        <img src="{{ Storage::url($item->product->photo) }}" alt="{{ $item->product->name }}" class="img-fluid rounded">
                                    @else
                                        <div class="bg-light rounded p-2 text-center">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h5 class="mb-1">{{ $item->product->name }}</h5>
                                    <p class="text-muted mb-0">${{ number_format($item->product->price / 100, 2) }}</p>
                                </div>
                                <div class="col-md-3">
                                    <form action="{{ route('cart.update_quantity', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control form-control-sm" onchange="this.form.submit()">
                                    </form>
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-0">${{ number_format($item->quantity * $item->product->price / 100, 2) }}</p>
                                </div>
                                <div class="col-md-1">
                                    <form action="{{ route('cart.remove_item', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this item?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        
                        <!-- Voucher Form -->
                       

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($items->sum(function($item) { return $item->quantity * $item->product->price; }) / 100, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong>${{ number_format($items->sum(function($item) { return $item->quantity * $item->product->price; }) / 100, 2) }}</strong>
                        </div>
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">Proceed to Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 