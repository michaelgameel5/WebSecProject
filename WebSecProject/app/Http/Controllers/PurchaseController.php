<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::where('user_id', Auth::id())
                           ->with(['items.product', 'card'])
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('purchases.index', compact('purchases'));
    }

    public function show(Purchase $purchase)
    {
        if ($purchase->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action');
        }

        $purchase->load(['items.product', 'card']);
        return view('purchases.show', compact('purchase'));
    }
}
