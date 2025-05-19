<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        // Removed all middleware usage
    }

    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function create()
    {
        if (!auth()->user()->can('add_products')) {
            abort(403, 'Unauthorized');
        }
        return view('products.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('add_products')) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
            $validated['photo'] = $photoPath;
        }
        Product::create($validated);
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        if (!auth()->user()->can('edit_products')) {
            abort(403, 'Unauthorized');
        }
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if (!auth()->user()->can('edit_products')) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $photoPath = $request->file('photo')->store('products', 'public');
            $validated['photo'] = $photoPath;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->can('delete_products')) {
            abort(403, 'Unauthorized');
        }
        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }
        
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function purchase(Request $request)
    {
        if (!auth()->check() || !auth()->user()->cannot('purchase_products')) {
            abort(403, 'Unauthorized');
        }
        // ... existing code ...
    }
} 