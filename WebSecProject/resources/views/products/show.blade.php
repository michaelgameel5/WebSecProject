@extends('layouts.master')

@section('title', $product->name)
@section('content')
    <div class="card shadow-sm mx-auto" style="max-width: 900px;">
        <div class="row g-0 align-items-center">
            <div class="col-md-4 text-center p-4">
                @if($product->photo)
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="img-fluid rounded mb-3" style="max-height: 220px;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 220px;">
                        <span class="text-muted">No photo available</span>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h2 class="card-title fw-bold display-6 mb-3">{{ $product->name }}</h2>
                    <p class="card-text mb-3">{!! nl2br(e($product->description ?: 'No description available')) !!}</p>
                    <ul class="list-unstyled mb-3">
                        <li><strong>Price:</strong> ${{ number_format($product->price, 2) }}</li>
                        <li><strong>Stock:</strong> {{ $product->stock }} units</li>
                        <li><strong>Created:</strong> {{ $product->created_at->format('F j, Y') }}</li>
                        <li><strong>Last Updated:</strong> {{ $product->updated_at->format('F j, Y') }}</li>
                    </ul>
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 