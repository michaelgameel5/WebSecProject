@extends('layouts.simple')
@section('title', 'All Products')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">All Products</h2>
        <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">Add New Product</a>
    </div>
    <form method="GET" action="{{ route('products.index') }}" class="mb-4 flex flex-wrap gap-2 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="border rounded px-3 py-2" />
        <select name="sort" class="border rounded px-3 py-2">
            <option value="">Sort by price</option>
            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Price: Low to High</option>
            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Price: High to Low</option>
        </select>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Search</button>
    </form>
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->photo)
                                <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded" />
                            @else
                                <span class="text-gray-400">No image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection 