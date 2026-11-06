<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Для собак', 'slug' => 'dogs', 'icon' => '🐕', 'description' => 'Всё для счастливых прогулок и здорового питания'],
            ['name' => 'Для кошек', 'slug' => 'cats', 'icon' => '🐈', 'description' => 'Уют, игры и забота о ваших кошках'],
            ['name' => 'Для птиц', 'slug' => 'birds', 'icon' => '🦜', 'description' => 'Корм и аксессуары для пернатых друзей'],
            ['name' => 'Для грызунов', 'slug' => 'small-pets', 'icon' => '🐹', 'description' => 'Комфортная жизнь маленьких питомцев'],
        ])->mapWithKeys(fn (array $category) => [
            $category['slug'] => Category::updateOrCreate(['slug' => $category['slug']], $category),
        ]);

        $brands = collect([
            ['name' => 'PetJoy', 'slug' => 'petjoy', 'description' => 'Забота, которая каждый день рядом'],
            ['name' => 'Happy Tail', 'slug' => 'happy-tail', 'description' => 'Для весёлых хвостов и долгих прогулок'],
            ['name' => 'Purrfect', 'slug' => 'purrfect', 'description' => 'Всё для мурлыкающего счастья'],
        ])->mapWithKeys(fn (array $brand) => [
            $brand['slug'] => Brand::updateOrCreate(['slug' => $brand['slug']], $brand),
        ]);

        $products = [
            ['dogs', 'petjoy', 'Полнорационный корм с индейкой', 'dog-food-turkey', 1890, 1590, 24, false, true, 'Мягкий вкусный корм для ежедневного рациона взрослых собак.'],
            ['cats', 'purrfect', 'Игрушка-мышка с кошачьей мятой', 'catnip-mouse', 490, null, 18, true, true, 'Лёгкая игрушка для охотничьих игр дома.'],
            ['cats', 'purrfect', 'Миска керамическая «Луна»', 'ceramic-bowl-moon', 890, 690, 12, false, true, 'Устойчивая керамическая миска с гладкой поверхностью.'],
            ['dogs', 'happy-tail', 'Поводок для прогулок, 2 м', 'walking-leash', 1290, null, 9, true, false, 'Прочный и удобный поводок для ежедневных приключений.'],
            ['birds', 'petjoy', 'Зерновая смесь для волнистых попугаев', 'parakeet-seed-mix', 590, null, 32, false, false, 'Сбалансированная смесь для разнообразного питания.'],
            ['small-pets', 'petjoy', 'Домик-тоннель для грызунов', 'small-pet-tunnel', 790, null, 7, true, false, 'Уютное место для игр и отдыха маленького питомца.'],
            ['cats', 'purrfect', 'Когтеточка «Облачко»', 'cloud-scratcher', 2490, 2190, 6, false, true, 'Компактная когтеточка с мягкой площадкой для отдыха.'],
            ['dogs', 'happy-tail', 'Мяч для активных игр', 'active-play-ball', 650, null, 15, true, false, 'Лёгкий мяч для игр дома и на свежем воздухе.'],
        ];

        foreach ($products as [$category, $brand, $name, $slug, $price, $salePrice, $stock, $isNew, $featured, $description]) {
            Product::updateOrCreate(['slug' => $slug], [
                'category_id' => $categories[$category]->id,
                'brand_id' => $brands[$brand]->id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'sale_price' => $salePrice,
                'stock' => $stock,
                'is_new' => $isNew,
                'is_featured' => $featured,
            ]);
        }

        foreach ([
            ['Алина', 'Пёс доволен каждой прогулкой, а доставка приехала быстро. Спасибо!', 5],
            ['Марк', 'Наш кот сразу оценил новую игрушку. Теперь это его любимая мышка.', 5],
            ['Екатерина', 'Очень приятный магазин: помогли подобрать корм и ответили на все вопросы.', 5],
        ] as [$name, $text, $rating]) {
            Review::firstOrCreate(
                ['author_name' => $name, 'text' => $text],
                ['rating' => $rating, 'is_approved' => true],
            );
        }
    }
}
