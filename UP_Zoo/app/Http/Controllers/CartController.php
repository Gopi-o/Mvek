<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = collect(session('cart', []));
        $products = Product::whereIn('id', $cart->keys())->get()->keyBy('id');

        $items = $cart->map(fn ($quantity, $id) => isset($products[$id])
            ? ['product' => $products[$id], 'quantity' => $quantity]
            : null)->filter();

        return view('pages.cart', [
            'items' => $items,
            'total' => $items->sum(fn ($item) => (float) $item['product']->current_price * $item['quantity']),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate(['quantity' => ['nullable', 'integer', 'min:1', 'max:99']]);
        $quantity = $validated['quantity'] ?? 1;

        if ($product->stock < $quantity) {
            return back()->withErrors(['cart' => 'Такого количества сейчас нет в наличии.']);
        }

        $cart = session('cart', []);
        $cart[$product->id] = min($product->stock, ($cart[$product->id] ?? 0) + $quantity);
        session(['cart' => $cart]);

        return back()->with('success', 'Товар добавлен в корзину.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);

        if ($validated['quantity'] > $product->stock) {
            return back()->withErrors(['cart' => 'Такого количества сейчас нет в наличии.']);
        }

        $cart = session('cart', []);
        $cart[$product->id] = $validated['quantity'];
        session(['cart' => $cart]);

        return to_route('cart.index')->with('success', 'Корзина обновлена.');
    }

    public function destroy(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return to_route('cart.index')->with('success', 'Товар удалён из корзины.');
    }
}
