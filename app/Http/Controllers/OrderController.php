<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Корзина пуста!');
        }

        $total = 0;
        foreach ($cart as $productId => $item) {
            $product = \App\Models\Product::find($productId);
            if ($product) {
                $total += $item['quantity'] * $product->price;
            }
        }

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'address' => $request->address,
            'delivery' => $request->delivery ?? 'delivery',
            'total' => $total,
            'status' => 'new'
        ]);

        foreach ($cart as $productId => $item) {
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        session()->forget('cart');

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Заказ #' . $order->id . ' успешно оформлен!');
    }

    public function show(Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(19);
        return view('orders.index', compact('orders'));
    }

}
