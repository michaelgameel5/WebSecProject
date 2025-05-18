<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        // Check if product is in stock
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        $totalCost = ($product->price / 100) * $request->quantity;
        $user = Auth::user();

        // Check if user has enough credit
        if ($user->credit < $totalCost) {
            return redirect()->back()->with('error', 'Not enough credit. You need $' . number_format($totalCost, 2) . ' but have $' . number_format($user->credit, 2));
        }

        // Create the order
        $order = new Order([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price_at_purchase' => $product->price / 100, // Convert cents to dollars
        ]);

        // Update product stock
        $product->stock -= $request->quantity;
        $product->save();

        // Deduct credit from user
        $user->credit -= $totalCost;
        $user->save();

        $order->save();

        return redirect()->route('products.show', $product)
            ->with('success', 'Product purchased successfully! $' . number_format($totalCost, 2) . ' has been deducted from your credit.');
    }

    public function index()
    {
        $orders = Order::with(['product', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function checkout()
    {
        $user = Auth::user();
        $orders = Order::with(['product'])
            ->where('user_id', $user->id)
            ->whereNull('checkout_at')
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->route('orders.index')
                ->with('error', 'No pending orders to checkout.');
        }

        $total = $orders->sum(function ($order) {
            return $order->price_at_purchase * $order->quantity;
        });

        return view('orders.checkout', compact('orders', 'total', 'user'));
    }

    public function processCheckout()
    {
        $user = Auth::user();
        
        DB::beginTransaction();
        try {
            $orders = Order::with(['product'])
                ->where('user_id', $user->id)
                ->whereNull('checkout_at')
                ->get();

            if ($orders->isEmpty()) {
                return redirect()->route('orders.index')
                    ->with('error', 'No pending orders to checkout.');
            }

            $total = $orders->sum(function ($order) {
                return $order->price_at_purchase * $order->quantity;
            });

            if ($user->credit < $total) {
                return redirect()->route('orders.checkout')
                    ->with('error', 'Insufficient credit to complete the purchase.');
            }

            // Update orders with checkout timestamp
            foreach ($orders as $order) {
                $order->checkout_at = now();
                $order->save();
            }

            // Deduct credit
            $user->credit -= $total;
            $user->save();

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Checkout completed successfully! $' . number_format($total, 2) . ' has been deducted from your credit.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('orders.checkout')
                ->with('error', 'An error occurred during checkout. Please try again.');
        }
    }
} 