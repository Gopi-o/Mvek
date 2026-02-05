<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard($tab = 'products')
    {
        $data = [];
        
        switch ($tab) {
            case 'categories':
                $data['items'] = Category::select('id', 'name', 'slug', 'created_at')->latest()->get();
                $data['tab'] = 'categories';
                break;
                
            case 'users':
                $data['items'] = User::select('id', 'name', 'email', 'is_admin', 'created_at')->latest()->get();
                $data['tab'] = 'users';
                break;
                
            default:
                $data['items'] = Product::with('category')->select('id', 'name', 'price', 'image', 'category_id', 'created_at')->latest()->get();
                $data['tab'] = 'products';
                break;
        }
        
        $data['categories'] = Category::all();
        
        return view('admin.dashboard', $data);
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string' 
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description ?? 'Описание товара', 
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => 'default.jpg',
            'stock' => 0
        ]);

        return back()->with('success', 'Товар создан');
    }

    public function editProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string'
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description ?? $product->description,
            'price' => $request->price,
            'category_id' => $request->category_id
        ]);

        return back()->with('success', 'Товар обновлен');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Товар удален');
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name)
        ]);

        return back()->with('success', 'Категория создана');
    }

    public function editCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name)
        ]);

        return back()->with('success', 'Категория обновлена');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Категория удалена');
    }

    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_admin' => !$user->is_admin]);

        return back()->with('success', 'Статус администратора изменен');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Пользователь удален');
    }
}