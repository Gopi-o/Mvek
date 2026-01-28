<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
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
            $productId = $request->input('product_id');
            
            $product = \App\Models\Product::findOrFail($productId);
            
            $cart = Session::get('cart', []);
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity']++;
            } else {
                $cart[$productId] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'quantity' => 1
                ];
            }
            
            Session::put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Товар добавлен в корзину',
                'count' => $cart[$productId]['quantity'],
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $cart = session('cart', []);
        foreach ($request->all() as $id => $quantity) {
            if (str_starts_with($id, 'quantity_') && $quantity > 0) {
                $productId = str_replace('quantity_', '', $id);
                $cart[$productId]['quantity'] = (int)$quantity;
            }
        }
        session(['cart' => $cart]);
        
        return redirect()->route('cart.index')->with('success', 'Корзина обновлена!');
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = Session::get('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Товар удален'
        ]);
    }

    public function clear()
    {
        Session::forget('cart');
        return response()->json(['success' => true]);
    }

    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста!');
        }
        
        $products = Product::whereIn('id', array_keys($cart))->with('category')->get();
        $total = 0;
        foreach ($cart as $id => $item) {
            $product = $products->firstWhere('id', $id);
            if ($product) $total += $item['quantity'] * $product->price;
        }
        
        return view('cart.checkout', compact('cart', 'products', 'total'));
    }
}
