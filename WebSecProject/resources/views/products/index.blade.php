@extends('layouts.master')
@section('title', 'Products')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Products</h1>
        @role('employee')
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

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Description</th>
                    @role('employee')
                        <th>Actions</th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            @if($product->photo)
                                <img src="{{ Storage::url($product->photo) }}" alt="{{ $product->name }}" style="max-width: 100px;" class="rounded">
                            @else
                                <div class="bg-light rounded p-2 text-center">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none">{{ $product->name }}</a>
                        </td>
                        <td>${{ number_format($product->price / 100, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ Str::limit($product->description, 50) }}</td>
                        @role('employee')
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endrole
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 