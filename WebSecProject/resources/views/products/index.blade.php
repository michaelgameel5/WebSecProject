@extends('layouts.master')
@section('title', 'Products')
@section('content')
<div class="container mt-4">
    <div class="products-hero">
        <h1>Products</h1>
        @role('employee|admin')
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Product
        </a>
        @endrole
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="products-controls">
        <form method="GET" action="{{ route('products.index') }}" class="mb-0">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by product name..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i> Search</button>
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            </div>
        </form>
        <div class="d-flex align-items-center gap-2">
            <span class="me-2">Sort by price:</span>
            <a href="{{ route('products.index', array_merge(request()->except('page'), ['sort' => 'price_asc'])) }}" class="btn btn-outline-primary btn-sm @if(request('sort')=='price_asc') active @endif">
                <i class="fas fa-sort-amount-up-alt"></i> Ascending
            </a>
            <a href="{{ route('products.index', array_merge(request()->except('page'), ['sort' => 'price_desc'])) }}" class="btn btn-outline-primary btn-sm @if(request('sort')=='price_desc') active @endif">
                <i class="fas fa-sort-amount-down-alt"></i> Descending
            </a>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    @if($product->photo)
                        <img src="{{ asset($product->photo) }}" alt="{{ $product->name }}" class="card-img-top object-fit-cover" style="height: 200px; width: 100%;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1 text-center">
                            <a href="{{ route('products.show', $product) }}" class="product-link">{{ $product->name }}</a>
                        </h5>
                        <p class="card-text text-primary fw-bold mb-1">${{ number_format($product->price / 100, 2) }}</p>
                        <p class="card-text mb-1"><span class="badge bg-secondary">Stock: {{ $product->stock }}</span></p>
                        <p class="card-text text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                        <div class="mt-auto">
                            <div class="d-flex gap-2">
                                @role('employee|admin')
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm flex-fill">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this product?')">
                                        <i class="fas fa-trash-alt me-1"></i>Delete
                                    </button>
                                </form>
                                @endrole
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">No products found.</div>
        @endforelse
    </div>
</div>

<style>
    .products-hero {
        background: var(--primary-color);
        color: #fff;
        border-radius: 1rem;
        padding: 1.5rem 2rem 1rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 12px rgba(74,144,226,0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .products-hero h1 {
        font-size: 2.1rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: 0.01em;
    }
    .products-hero .btn {
        font-size: 1em;
        font-weight: 600;
        border-radius: 2em;
        box-shadow: 0 2px 8px rgba(74,144,226,0.08);
    }
    .products-controls {
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
    }
    .products-controls .input-group {
        max-width: 350px;
    }
    .products-controls .btn-outline-primary.active {
        background: var(--primary-color);
        color: #fff;
        border-color: var(--primary-color);
    }
    .card {
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(44,62,80,0.07);
        transition: box-shadow 0.2s, transform 0.15s;
        border: none;
    }
    .card:hover {
        box-shadow: 0 8px 24px rgba(44,62,80,0.13);
        transform: translateY(-3px) scale(1.01);
    }
    .card-img-top {
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        object-fit: cover;
        background: #f8f9fa;
    }
    .card-body {
        padding-bottom: 1.2rem;
    }
    .badge.bg-secondary {
        background: var(--secondary-color) !important;
        font-size: 0.95em;
        padding: 0.4em 0.8em;
        border-radius: 1em;
    }
    .btn-warning, .btn-danger {
        border-radius: 1.5em;
        font-weight: 600;
    }
    .btn-warning {
        background: #ffc107;
        color: #212529;
        border: none;
    }
    .btn-warning:hover {
        background: #ffcd39;
        color: #212529;
    }
    .btn-danger {
        background: var(--accent-color);
        color: #fff;
        border: none;
    }
    .btn-danger:hover {
        background: #c0392b;
        color: #fff;
    }
    .alert-success {
        border-radius: 1em;
        font-size: 1.1em;
    }
    @media (max-width: 576px) {
        .products-hero {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.2rem 1rem 1rem 1rem;
        }
        .products-hero h1 {
            font-size: 1.5rem;
        }
        .products-controls {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    .product-link {
        color: var(--primary-color);
        background: none;
        padding: 0;
        border-radius: 0;
        font-weight: 600;
        box-shadow: none;
        text-decoration: none;
        transition: color 0.2s, text-decoration 0.2s;
        font-size: 1.1em;
    }
    .product-link:hover, .product-link:focus {
        color: #0a58ca;
        text-decoration: underline;
        background: none;
        box-shadow: none;
        transform: none;
    }
</style>
@endsection