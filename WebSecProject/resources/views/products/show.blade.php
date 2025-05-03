@extends('layouts.simple')
@section('title', $product->name)
@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-8">
        <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-shrink-0">
                @if($product->photo)
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="w-64 h-64 object-cover rounded-lg border">
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
    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-8 mt-8">
        <h3 class="text-lg font-semibold mb-4">Comments</h3>
        @foreach($product->comments as $comment)
            <div class="mb-2 p-3 border rounded">
                <div class="text-sm text-gray-700 font-bold">{{ $comment->user->name ?? 'Unknown' }}</div>
                <div class="text-gray-800">{{ $comment->content }}</div>
                <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
            </div>
        @endforeach
        @if($product->comments->isEmpty())
            <div class="text-gray-500">No comments yet.</div>
        @endif
        @auth
        <form action="{{ route('products.addComment', $product) }}" method="POST" class="mt-4">
            @csrf
            <textarea name="content" rows="3" class="w-full border rounded p-2" placeholder="Add a comment..." required></textarea>
            <button type="submit" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Post Comment</button>
        </form>
        @else
        <div class="mt-4 text-gray-600">You must be <a href="{{ route('login') }}" class="text-blue-600 underline">logged in</a> to comment.</div>
        @endauth
    </div>
@endsection 