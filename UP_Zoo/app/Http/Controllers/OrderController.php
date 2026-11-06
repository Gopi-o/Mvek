<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function create()
    {
        if (empty(session('cart', []))) {
            return to_route('cart.index');
        }

        return view('pages.checkout');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $cart = session('cart', []);

        if ($cart === []) {
            return to_route('cart.index')->withErrors(['cart' => 'Корзина пуста.']);
        }

        $order = DB::transaction(function () use ($cart, $validated, $request) {
            $products = Product::whereIn('id', array_keys($cart))->lockForUpdate()->get()->keyBy('id');
            $total = 0;

            foreach ($cart as $productId => $quantity) {
                if (! isset($products[$productId]) || $products[$productId]->stock < $quantity) {
                    throw ValidationException::withMessages(['cart' => 'Один из товаров закончился или изменилось его количество.']);
                }

                $total += (float) $products[$productId]->current_price * $quantity;
            }

            $order = Order::create([
                ...$validated,
                'user_id' => $request->user()?->id,
                'total' => $total,
                'status' => 'new',
            ]);

            foreach ($cart as $productId => $quantity) {
                $product = $products[$productId];
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->current_price,
                    'quantity' => $quantity,
                ]);
                $product->decrement('stock', $quantity);
            }

            return $order;
        });

        session()->forget('cart');

        $redirect = $order->user_id ? to_route('account') : to_route('home');

        return $redirect->with('success', "Заказ №{$order->id} успешно оформлен.");
    }
}
