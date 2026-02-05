<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'VR-очки', 'slug' => 'vr-ochki'],
            ['name' => 'Аксессуары', 'slug' => 'aksessuary'],
            ['name' => 'Игры для VR', 'slug' => 'vr-games'],
            ['name' => 'Крепления и ремни', 'slug' => 'straps'],
            ['name' => 'Оптика и линзы', 'slug' => 'lenses'],
            ['name' => 'Кабели и адаптеры', 'slug' => 'cables'],
            ['name' => 'Трекинг и базы', 'slug' => 'tracking'],
            ['name' => 'Аудио и микрофоны', 'slug' => 'audio'],
            ['name' => 'Сумки и хранение', 'slug' => 'cases'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name']]
            );
        }
    }
}

