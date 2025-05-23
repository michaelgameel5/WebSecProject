@extends('layouts.master')
@section('title', $product->name)
@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            @if($product->photo)
                <img src="{{ asset($product->photo) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow">
            @else
                <div class="bg-light rounded p-5 text-center shadow">
                    <i class="fas fa-image fa-3x text-muted"></i>
                    <p class="mt-2 text-muted">No image available</p>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="lead">${{ number_format($product->price / 100, 2) }}</p>
            <p><strong>Stock:</strong> {{ $product->stock }}</p>
            <p><strong>Description:</strong></p>
            <p>{{ $product->description }}</p>
            
            @auth
                @if($product->stock > 0)
                    @role('customer')
                    <form action="{{ route('orders.store', $product) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                    id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart me-1"></i>Purchase
                                </button>
                            </div>
                        </div>
                    </form>
                    @endrole
                @else
                    <div class="alert alert-warning mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>This product is out of stock.
                    </div>
                @endif
            @else
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>Please <a href="{{ route('login') }}" class="alert-link">login</a> to purchase this product.
                </div>
            @endauth
            
            <div class="mt-4">
                @role('employee|admin')
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                        <i class="fas fa-trash-alt me-1"></i>Delete
                    </button>
                </form>
                @endrole
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3><i class="fas fa-comments me-2"></i>Comments</h3>
            
            @auth
                <form action="{{ route('comments.store', $product) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="form-group">
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" placeholder="Write a comment..." required></textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">
                        <i class="fas fa-paper-plane me-1"></i>Post Comment
                    </button>
                </form>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Please <a href="{{ route('login') }}" class="alert-link">login</a> to leave a comment.
                </div>
            @endauth

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

            <div class="comments-list">
                @forelse($product->comments as $comment)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <i class="fas fa-user me-1"></i>{{ $comment->user->name }}
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $comment->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <p class="card-text">{{ $comment->content }}</p>
                            @if(auth()->check() && auth()->id() === $comment->user_id)
                                @can('manage_comments')
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this comment?')">
                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                        </button>
                                    </form>
                                @endcan
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>No comments yet. Be the first to comment!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection