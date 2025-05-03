@extends('layouts.simple')
@section('title', $product->name)
@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-8">
        <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-shrink-0">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-64 h-64 object-cover rounded-lg border">
                @else
                    <div class="w-64 h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-400">No image available</span>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h2>
                <p class="mb-4 text-gray-600">{{ $product->description ?: 'No description available' }}</p>
                <div class="mb-2"><span class="font-semibold">Price:</span> ${{ number_format($product->price, 2) }}</div>
                <div class="mb-2"><span class="font-semibold">Stock:</span> {{ $product->stock }} units</div>
                <div class="mb-2">
                    <span class="font-semibold">Status:</span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="mb-2"><span class="font-semibold">Created:</span> {{ $product->created_at->format('F j, Y') }}</div>
                <div class="mb-2"><span class="font-semibold">Last Updated:</span> {{ $product->updated_at->format('F j, Y') }}</div>
                <div class="mt-6 flex gap-2">
                    <a href="{{ route('products.edit', $product) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded shadow">Edit</a>
                    <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection 