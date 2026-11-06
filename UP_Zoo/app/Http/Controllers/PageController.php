<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'categories' => Category::withCount('products')->get(),
            'products' => Product::with(['category', 'brand'])->where('is_featured', true)->take(4)->get(),
            'reviews' => Review::where('is_approved', true)->latest()->take(3)->get(),
        ]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function promotions()
    {
        return view('pages.products', [
            'title' => 'Акции',
            'subtitle' => 'Приятные цены на любимые товары',
            'products' => Product::with(['category', 'brand'])->whereNotNull('sale_price')->paginate(12),
        ]);
    }

    public function newProducts()
    {
        return view('pages.products', [
            'title' => 'Новинки',
            'subtitle' => 'Свежие находки для ваших питомцев',
            'products' => Product::with(['category', 'brand'])->where('is_new', true)->latest()->paginate(12),
        ]);
    }

    public function brands()
    {
        return view('pages.brands', [
            'brands' => Brand::withCount('products')->orderBy('name')->get(),
            'products' => Product::with(['category', 'brand'])
                ->when(request('brand'), fn ($query, $slug) => $query->whereHas('brand', fn ($brand) => $brand->where('slug', $slug)))
                ->paginate(12),
        ]);
    }

    public function contacts()
    {
        return view('pages.contacts');
    }
}
