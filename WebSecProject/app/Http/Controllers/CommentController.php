<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Product $product)
    {
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
        // Only allow users to delete their own comments
        if ($comment->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }

        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
} 