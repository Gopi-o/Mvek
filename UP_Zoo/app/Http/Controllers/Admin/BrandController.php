<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function index()
    {
        return view('admin.brands.index', [
            'brands' => Brand::withCount('products')->orderBy('name')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.brands.form', ['brand' => new Brand]);
    }

    public function store(Request $request)
    {
        Brand::create($this->validated($request));

        return to_route('admin.brands.index')->with('success', 'Бренд добавлен.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.form', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $brand->update($this->validated($request, $brand));

        return to_route('admin.brands.index')->with('success', 'Бренд обновлён.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return to_route('admin.brands.index')->with('success', 'Бренд удалён.');
    }

    private function validated(Request $request, ?Brand $brand = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('brands')->ignore($brand)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
