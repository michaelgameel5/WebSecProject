<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%") ;
            });
        }

        // Sorting
        if ($request->filled('sort') && in_array($request->input('sort'), ['asc', 'desc'])) {
            $query->orderBy('price', $request->input('sort'));
        } else {
            $query->latest();
        }

        $products = $query->paginate(10)->appends($request->all());
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
            $validated['photo'] = $photoPath;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['comments.user']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
            $validated['photo'] = $photoPath;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function addComment(Request $request, Product $product)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $comment = new Comment([
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
        ]);
        $product->comments()->save($comment);
        return redirect()->route('products.show', $product)->with('success', 'Comment added!');
    }
}
