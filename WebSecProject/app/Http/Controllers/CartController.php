<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Card;
use App\Models\Voucher;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $items = $cart->items()->with('product')->get();
        $total = $items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
        $cards = Card::where('user_id', Auth::id())
                    ->where('is_active', true)
                    ->get();

        return view('cart.index', compact('items', 'total', 'cards'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        
        $cartItem = $cart->items()->where('product_id', $request->product_id)->first();
        
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Adding this quantity would exceed available stock');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        // Decrease the product stock
        $product->update([
            'stock' => $product->stock - $request->quantity
        ]);

        return back()->with('success', sprintf('%d %s added to your cart', $request->quantity, str($product->name)->plural($request->quantity)));
    }

    public function updateQuantity(Request $request, CartItem $item)
    {
        if ($item->cart->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item->update(['quantity' => $validated['quantity']]);
        return back()->with('success', 'Cart updated');
    }

    public function removeItem(CartItem $item)
    {
        if ($item->cart->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action');
        }

        $item->delete();
        return back()->with('success', 'Item removed from cart');
    }

    public function applyVoucher(Request $request)
    {
        $validated = $request->validate([
            'voucher_code' => 'required|string'
        ]);

        $voucher = Voucher::where('code', $validated['voucher_code'])
                         ->where('is_used', false)
                         ->where(function ($query) {
                             $query->whereNull('expires_at')
                                   ->orWhere('expires_at', '>', now());
                         })
                         ->first();

        if (!$voucher) {
            return back()->with('error', 'Invalid or expired voucher code');
        }

        session(['applied_voucher' => $voucher]);
        return back()->with('success', 'Voucher applied successfully');
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $total = $this->calculateTotal($user);
        
        // Convert total from cents to dollars
        $totalInDollars = $total / 100;

        // Deduct the total amount from the user's credits
        if ($user->credit < $totalInDollars) {
            return redirect()->route('cart.index')->with('error', 'Insufficient credit balance.');
        }

        $user->credit -= $totalInDollars;
        $user->save();

        // Create purchase record
        try {
            DB::beginTransaction();

            $purchase = Purchase::create([
                'user_id' => $user->id,
                'total_amount' => $totalInDollars,
                'status' => 'completed',
                'card_id' => null,
                'voucher_discount' => 0,
                'final_amount' => $totalInDollars
            ]);

            // Create purchase items
            $cart = Cart::where('user_id', $user->id)->first();
            foreach ($cart->items as $item) {
                $purchase->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price / 100,
                    'subtotal' => ($item->quantity * $item->product->price) / 100
                ]);
            }

            // Clear the cart
            $cart->items()->delete();

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Checkout completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing purchase: ' . $e->getMessage());
        }
    }

    private function calculateTotal($user)
    {
        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart || $cart->items()->count() === 0) {
            return 0;
        }

        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Apply voucher discount if exists
        if ($voucher = session('applied_voucher')) {
            $voucherDiscount = ($total * $voucher->discount_percentage) / 100;
            $total -= $voucherDiscount;
            $voucher->update(['is_used' => true]);
            session()->forget('applied_voucher');
        }

        return $total;
    }
} 