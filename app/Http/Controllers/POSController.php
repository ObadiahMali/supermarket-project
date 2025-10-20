<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class POSController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $products = Product::orderBy('name')->get(); // ✅ loads all products

        return view('pos.index', compact('cart', 'cartTotal', 'products'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'barcode' => 'nullable|string',
            'product_id' => 'nullable|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = null;

        if ($request->filled('barcode')) {
            $product = Product::where('barcode', $request->barcode)
                ->orWhere('name', 'like', "%{$request->barcode}%")
                ->first();
        } elseif ($request->filled('product_id')) {
            $product = Product::find($request->product_id);
        }

        if (! $product) {
            return back()->withErrors(['barcode' => 'Product not found']);
        }

        $cart = Session::get('cart', []);

        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => ($cart[$product->id]['quantity'] ?? 0) + $request->quantity,
        ];

        Session::put('cart', $cart);

        return redirect()->route('pos.index')->with('success', 'Product added to cart.');
    }

    public function removeFromCart($id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart', $cart);

        return redirect()->route('pos.index')->with('success', 'Item removed from cart.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:cash,mobile_money,card',
            'amount_received' => 'required|numeric|min:0',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['auth' => 'You must be logged in to checkout.']);
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('pos.index')->withErrors(['cart' => 'Cart is empty.']);
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $change = $request->amount_received - $total;

        if ($change < 0) {
            return back()->withErrors(['amount_received' => 'Amount received is less than total.']);
        }

        $sale = Sale::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'payment_method' => $request->payment_method,
        ]);

        foreach ($cart as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            $product = Product::find($item['id']);
            if ($product && $product->stock >= $item['quantity']) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        $pdf = Pdf::loadView('receipts.sale', compact('cart', 'total', 'sale', 'change'));
        Session::forget('cart');

        return $pdf->download('receipt_' . now()->format('Ymd_His') . '.pdf');
    }
}