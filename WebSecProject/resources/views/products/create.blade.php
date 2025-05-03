@extends('layouts.simple')
@section('title', 'Create Product')
@section('content')
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Create Product</h2>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" name="price" id="price" step="0.01" value="{{ old('price') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('stock')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
                <input type="file" name="photo" id="photo" class="mt-1 block w-full">
                @error('photo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="is_active" id="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="1" {{ old('is_active') ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">Create Product</button>
            </div>
        </form>
    </div>
@endsection 