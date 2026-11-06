<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function index()
    {
        return view('pages.reviews', [
            'reviews' => Review::where('is_approved', true)->latest()->paginate(12),
            'products' => Product::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'text' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $hasPurchased = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.user_id', $request->user()->id)
            ->where('order_items.product_id', $validated['product_id'])
            ->exists();

        if (! $hasPurchased) {
            throw ValidationException::withMessages([
                'product_id' => 'Оставить отзыв можно после покупки этого товара.',
            ]);
        }

        Review::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'author_name' => $request->user()->name,
        ]);

        return to_route('reviews.index')->with('success', 'Спасибо за отзыв! Он появится после проверки.');
    }
}
