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
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id'
        ]);

        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart || $cart->items()->count() === 0) {
            return back()->with('error', 'Cart is empty');
        }

        $card = Card::findOrFail($validated['card_id']);
        if ($card->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action');
        }

        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $voucherDiscount = 0;
        // Apply voucher discount if exists
        if ($voucher = session('applied_voucher')) {
            // Calculate discount based on percentage
            $voucherDiscount = ($total * $voucher->discount_percentage) / 100;
            $total -= $voucherDiscount;
            $voucher->update(['is_used' => true]);
            session()->forget('applied_voucher');
        }

        // Convert total to dollars (divide by 100)
        $totalInDollars = $total / 100;
        $voucherDiscountInDollars = $voucherDiscount / 100;

        if ($card->credit_balance < $totalInDollars) {
            return back()->with('error', 'Insufficient balance');
        }

        try {
            DB::beginTransaction();

            // Create purchase record
            $purchase = Purchase::create([
                'user_id' => Auth::id(),
                'card_id' => $card->id,
                'total_amount' => ($total + $voucherDiscount) / 100,
                'voucher_discount' => $voucherDiscountInDollars,
                'final_amount' => $totalInDollars,
                'status' => 'completed'
            ]);

            // Create purchase items
            foreach ($cart->items as $item) {
                $purchase->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price / 100,
                    'subtotal' => ($item->quantity * $item->product->price) / 100
                ]);
            }

            // Deduct from card balance
            $card->update([
                'credit_balance' => $card->credit_balance - $totalInDollars
            ]);

            // Clear the cart
            $cart->items()->delete();

            DB::commit();
            return redirect()->route('purchases.index')
                           ->with('success', 'Purchase completed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing purchase: ' . $e->getMessage());
        }
    }
} 