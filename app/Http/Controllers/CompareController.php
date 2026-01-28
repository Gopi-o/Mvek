<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;     
use App\Models\Comparer;    
use Illuminate\Support\Facades\Auth;

class CompareController extends Controller
{
    public function index()
    {
        $userId = Auth::id() ?? session()->get('guest_id');
        
        $products = Product::whereHas('comparers', function($q) use($userId) {
            $q->where('user_id', $userId);
        })->with('category')->get();
        
        return view('compare.index', compact('products'));
    }
    
    
    public function add(Request $request) {
        $request->validate(['product_id' => 'required|exists:products,id']);
        
        $userId = Auth::id() ?? session()->get('guest_id', function() {
            $id = uniqid();
            session()->put('guest_id', $id);
            return $id;
        });
        
        Comparer::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $request->product_id]
        );
        
        return redirect()->route('compare.index')
            ->with('success', 'Товар добавлен в сравнение!');
    }

    public function remove(Request $request)
    {
        $userId = Auth::id() ?? session()->get('guest_id');
        Comparer::where('user_id', $userId)
                ->where('product_id', $request->product_id)
                ->delete();
        
        return redirect()->route('compare.index')
            ->with('success', 'Товар удален из сравнения!');
    }
}
