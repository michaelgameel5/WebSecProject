@extends('layouts.master')

@section('title', 'All Products')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold display-6">All Products</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary shadow">Add New Product</a>
    </div>
    <div class="bg-white shadow rounded-lg overflow-auto">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="py-3">{{ $product->name }}</td>
                        <td class="py-3">${{ number_format($product->price, 2) }}</td>
                        <td class="py-3">{{ $product->stock }}</td>
                        <td class="py-3">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-link text-primary p-0 me-2">View</a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-link text-warning p-0 me-2">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm ms-1" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">
            {{ $products->links() }}
        </div>
    </div>
@endsection 