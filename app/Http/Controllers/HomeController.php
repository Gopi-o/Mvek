<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::with('category')
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        $categories = Category::with(['products' => function($q) {
            $q->limit(4);
        }])->take(6)->get();
        
        return view('home', compact('featured', 'categories'));
    }
}
