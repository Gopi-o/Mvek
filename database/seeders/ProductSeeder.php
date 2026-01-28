<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vrCategory = Category::create(['name' => 'VR-очки', 'slug' => 'vr-ochki']);
        $accCategory = Category::create(['name' => 'Аксессуары', 'slug' => 'aksessuary']);

        Product::create([
            'name' => 'Oculus Quest 3', 
            'slug' => 'oculus-quest-3', 
            'description' => 'Топовые VR-очки 2025', 
            'price' => 599.99, 
            'image' => 'oculus.jpg', 
            'stock' => 10, 
            'category_id' => $vrCategory->id
        ]);
        Product::create([
            'name' => 'HTC Vive Pro 2', 
            'slug' => 'htc-vive-pro-2', 
            'description' => 'Профессиональные VR', 
            'price' => 799.99, 
            'image' => 'vive.jpg', 
            'stock' => 5, 
            'category_id' => $vrCategory->id
        ]);
        Product::create([
            'name' => 'Контроллеры VR', 
            'slug' => 'vr-controllers', 
            'description' => 'Удобные контроллеры', 
            'price' => 129.99, 
            'image' => 'controllers.jpg', 
            'stock' => 20, 
            'category_id' => $accCategory->id
        ]);
    }
}
