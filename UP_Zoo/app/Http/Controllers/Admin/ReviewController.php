<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index', [
            'reviews' => Review::with('product')->latest()->paginate(20),
        ]);
    }

    public function update(Request $request, Review $review)
    {
        $review->update($request->validate([
            'is_approved' => ['required', 'boolean'],
        ]));

        return to_route('admin.reviews.index')->with('success', 'Статус отзыва изменён.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return to_route('admin.reviews.index')->with('success', 'Отзыв удалён.');
    }
}
