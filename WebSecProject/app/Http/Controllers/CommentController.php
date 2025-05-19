<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        // Removed all middleware usage
    }

    public function store(Request $request, Product $product)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $comment = new Comment($validated);
        $comment->user_id = auth()->id();
        $comment->product_id = $product->id;
        $comment->save();
        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        if (!auth()->check() || !auth()->user()->can('manage_comments')) {
            abort(403, 'Unauthorized');
        }
        // Only allow users to delete their own comments
        if ($comment->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
} 