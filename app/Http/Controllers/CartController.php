<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartService::getItems();
        $products = Product::whereIn('id', array_keys($cart))->with('category')->get();

        $total = 0;
        foreach ($cart as $id => $item) {
            $product = $products->firstWhere('id', $id);
            if ($product) {
                $total += $item['quantity'] * $product->price;
            }
        }

        return view('cart.index', compact('products', 'cart', 'total'));
    }

    public function add(Request $request)
    {
        try {
            $productId = (int) $request->input('product_id');
            $quantity = (int) $request->input('quantity', 1) ?: 1;

            $result = CartService::add($productId, $quantity);

            return response()->json([
                'success' => true,
                'message' => 'Товар добавлен в корзину',
                'count' => $result['count'],
                'product' => $result['product'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $key => $quantity) {
            if (str_starts_with($key, 'quantity_') && $quantity > 0) {
                $productId = (int) str_replace('quantity_', '', $key);
                CartService::update($productId, (int) $quantity);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Корзина обновлена!');
    }

    public function remove(Request $request)
    {
        $productId = (int) $request->input('product_id');
        CartService::remove($productId);

        return response()->json([
            'success' => true,
            'message' => 'Товар удален',
        ]);
    }

    public function clear()
    {
        CartService::clear();
        return response()->json(['success' => true]);
    }

    public function checkout()
    {
        $cart = CartService::getItems();
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста!');
        }

        $products = Product::whereIn('id', array_keys($cart))->with('category')->get();
        $total = 0;
        foreach ($cart as $id => $item) {
            $product = $products->firstWhere('id', $id);
            if ($product) {
                $total += $item['quantity'] * $product->price;
            }
        }

        return view('cart.checkout', compact('cart', 'products', 'total'));
    }
}
