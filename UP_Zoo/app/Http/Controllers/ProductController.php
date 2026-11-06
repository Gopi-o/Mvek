<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'exists:categories,slug'],
            'brand' => ['nullable', 'exists:brands,slug'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $products = Product::with(['category', 'brand'])
            ->when($filters['q'] ?? null, fn ($query, $term) => $query->where('name', 'like', "%{$term}%"))
            ->when($filters['category'] ?? null, fn ($query, $slug) => $query->whereHas('category', fn ($category) => $category->where('slug', $slug)))
            ->when($filters['brand'] ?? null, fn ($query, $slug) => $query->whereHas('brand', fn ($brand) => $brand->where('slug', $slug)))
            ->when($filters['min_price'] ?? null, fn ($query, $price) => $query->whereRaw('COALESCE(sale_price, price) >= ?', [$price]))
            ->when($filters['max_price'] ?? null, fn ($query, $price) => $query->whereRaw('COALESCE(sale_price, price) <= ?', [$price]))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('pages.products', [
            'title' => 'Каталог',
            'subtitle' => 'Всё для счастливых хвостиков',
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function show(Product $product)
    {
        return view('pages.product', [
            'product' => $product->load(['category', 'brand']),
            'related' => Product::with('brand')
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->take(4)
                ->get(),
        ]);
    }
}
