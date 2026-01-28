<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

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
                $data['items'] = Product::select('id', 'name', 'price', 'image', 'created_at')->latest()->get();
                $data['tab'] = 'products';
                break;
        }
        
        return view('admin.dashboard', $data);
    }

    private function executeAction($action, $id, $params, $tab)
    {
        try {
            switch ($tab) {
                case 'products':
                    return $this->handleProducts($action, $id, $params);
                case 'categories':
                    return $this->handleCategories($action, $id, $params);
                case 'users':
                    return $this->handleUsers($action, $id, $params);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => '❌ Ошибка: ' . $e->getMessage()], 500);
        }
    }

    private function handleProducts($action, $id, $params)
    {
        $allowedFields = ['name', 'description', 'image', 'price', 'stock', 'category_id'];

        if ($action === 'add') {
            $params['name'] = $params['name'] ?? 'Новый товар';
            $params['slug'] = Str::slug($params['name']);
            $params['description'] = $params['description'] ?? 'Описание';
            $params['image'] = $params['image'] ?? 'default.jpg';
            $params['price'] = $params['price'] ?? 0;
            $params['stock'] = $params['stock'] ?? 0;
            $params['category_id'] = $params['category_id'] ?? 1;
            
            $product = Product::create($params);
            return response()->json(['success' => '✅ Создан товар ID: ' . $product->id]);
        }
        
        $product = Product::find($id);
        if (!$product) return response()->json(['error' => '❌ Товар не найден'], 404);
        
        if ($action === 'edit') {
            foreach ($params as $field => $value) {
                if (in_array($field, $allowedFields)) {
                    $product->$field = $value;
                }
            }
            if (isset($params['name'])) {
                $product->slug = Str::slug($params['name']);
            }
            $product->save();
            return response()->json(['success' => "✅ Обновлен товар #{$id}"]);
        }
        
        if ($action === 'delete') {
            $product->delete();
            return response()->json(['success' => "✅ Удален товар #{$id}"]);
        }
    }

    private function handleCategories($action, $id, $params)
    {
        if ($action === 'add') {
            $params['name'] = $params['name'] ?? 'Новая категория';
            $params['slug'] = Str::slug($params['name']);
            $category = Category::create($params);
            return response()->json(['success' => '✅ Создана категория ID: ' . $category->id]);
        }
        
        $category = Category::find($id);
        if (!$category) return response()->json(['error' => '❌ Категория не найдена'], 404);
        
        if ($action === 'edit') {
            if (isset($params['name'])) {
                $category->name = $params['name'];
                $category->slug = Str::slug($params['name']);
            }
            $category->save();
            return response()->json(['success' => "✅ Обновлена категория #{$id}"]);
        }
        
        if ($action === 'delete') {
            $category->delete();
            return response()->json(['success' => "✅ Удалена категория #{$id}"]);
        }
    }

    private function handleUsers($action, $id, $params)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => '❌ Пользователь не найден'], 404);
        
        if ($action === 'edit') {
            foreach ($params as $field => $value) {
                if (in_array($field, ['name', 'email', 'is_admin'])) {
                    $user->$field = $field === 'is_admin' ? (bool)$value : $value;
                }
            }
            $user->save();
            return response()->json(['success' => "✅ Обновлен пользователь #{$id}"]);
        }
        
        if ($action === 'delete') {
            $user->delete();
            return response()->json(['success' => "✅ Удален пользователь #{$id}"]);
        }
        
        return response()->json(['error' => 'Для пользователей: edit/delete'], 400);
    }
    
    private function parseParams($paramString)
    {
        $params = [];
        if (empty($paramString)) return $params;
        
        preg_match_all('/(\w+)\s*=\s*["\']?([^"\',]+)["\']?/i', $paramString, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $key = trim($match[1]);
            $value = trim($match[2], '"\' ');
            $params[$key] = is_numeric($value) ? (int)$value : $value;
        }
        return $params;
    }

    public function executeCommand(Request $request)
    {
        $tab = $request->input('tab', 'products');
        $command = trim($request->input('command'));
        
        if (preg_match('/^(\w+)\s*\(\s*(.+?)\s*\)$/i', $command, $matches)) {
            $action = strtolower($matches[1]);
            $argsString = trim($matches[2]);
            
            if ($action === 'add') {
                $params = $this->parseParams($argsString);
                return $this->executeAction($action, null, $params, $tab);
            }
            
            if (preg_match('/^(\d+)(?:,\s*(.+))?$/i', $argsString, $argMatches)) {
                $id = (int)$argMatches[1];
                $paramsString = $argMatches[2] ?? '';
                $params = $this->parseParams($paramsString);
                return $this->executeAction($action, $id, $params, $tab);
            }
        }
        
        return response()->json([
            'error' => '📝 Формат: action(id, param="value") или action(id) или add(param="value")'
        ], 400);
    }
}
