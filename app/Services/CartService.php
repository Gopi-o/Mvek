<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Получить содержимое корзины в едином формате.
     * Гости — из сессии, авторизованные — из БД.
     */
    public static function getItems(): array
    {
        if (Auth::check()) {
            return self::getFromDb();
        }
        return Session::get('cart', []);
    }

    /**
     * Получить корзину из БД для авторизованного пользователя.
     */
    protected static function getFromDb(): array
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'active'],
            ['status' => 'active']
        );

        $items = [];
        foreach ($cart->items()->with('product')->get() as $item) {
            $product = $item->product;
            if ($product) {
                $items[$product->id] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'quantity' => $item->quantity,
                ];
            }
        }
        return $items;
    }

    /**
     * Добавить товар в корзину.
     */
    public static function add(int $productId, int $quantity = 1): array
    {
        $product = Product::findOrFail($productId);

        if (Auth::check()) {
            return self::addToDb($product, $quantity);
        }
        return self::addToSession($product, $quantity);
    }

    protected static function addToDb(Product $product, int $quantity): array
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'active'],
            ['status' => 'active']
        );

        $item = CartItem::firstOrNew(['cart_id' => $cart->id, 'product_id' => $product->id]);
        $item->quantity = ($item->quantity ?? 0) + $quantity;
        $item->save();

        return [
            'success' => true,
            'count' => $item->quantity,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
            ],
        ];
    }

    protected static function addToSession(Product $product, int $quantity): array
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
            ];
        }

        Session::put('cart', $cart);

        return [
            'success' => true,
            'count' => $cart[$product->id]['quantity'],
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
            ],
        ];
    }

    /**
     * Обновить количество товара.
     */
    public static function update(int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return self::remove($productId);
        }

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->where('status', 'active')->first();
            if ($cart) {
                CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $productId)
                    ->update(['quantity' => $quantity]);
            }
            return true;
        }

        $cart = Session::get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }
        return true;
    }

    /**
     * Удалить товар из корзины.
     */
    public static function remove(int $productId): bool
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->where('status', 'active')->first();
            if ($cart) {
                CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->delete();
            }
            return true;
        }

        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);
        return true;
    }

    /**
     * Очистить корзину.
     */
    public static function clear(): bool
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->where('status', 'active')->each(function ($cart) {
                $cart->items()->delete();
            });
            return true;
        }

        Session::forget('cart');
        return true;
    }

    /**
     * Количество позиций в корзине.
     */
    public static function count(): int
    {
        $items = self::getItems();
        return (int) collect($items)->sum('quantity');
    }

    /**
     * Объединить сессионную корзину в БД при входе пользователя.
     */
    public static function mergeSessionToUser(int $userId): void
    {
        $sessionCart = Session::get('cart', []);
        if (empty($sessionCart)) {
            return;
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId, 'status' => 'active'],
            ['status' => 'active']
        );

        foreach ($sessionCart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $cartItem = CartItem::firstOrNew(['cart_id' => $cart->id, 'product_id' => $productId]);
                $cartItem->quantity = ($cartItem->quantity ?? 0) + ($item['quantity'] ?? 1);
                $cartItem->save();
            }
        }

        Session::forget('cart');
    }
}
