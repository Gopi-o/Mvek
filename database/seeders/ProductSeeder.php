<?php

namespace Database\Seeders;

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
        // Категории должны быть созданы через CategorySeeder.
        // На всякий случай используем firstOrCreate (если сиды запускаются выборочно).
        $requiredCategorySlugs = [
            'vr-ochki',
            'aksessuary',
            'vr-games',
            'straps',
            'lenses',
            'cables',
            'tracking',
            'audio',
            'cases',
        ];

        foreach ($requiredCategorySlugs as $slug) {
            Category::firstOrCreate(['slug' => $slug], ['name' => $slug]);
        }

        $categories = Category::whereIn('slug', $requiredCategorySlugs)->get()->keyBy('slug');

        $products = [
            // VR-очки
            [
                'name' => 'Meta Quest 3 (128GB)',
                'slug' => 'meta-quest-3-128',
                'description' => 'Беспроводной VR/MR-шлем с высоким разрешением, улучшенной оптикой и отличным трекингом. Идеален для игр, фитнеса и просмотра контента без ПК.',
                'price' => 59990,
                'image' => 'Meta_Quest_3_128GB.jpeg',
                'stock' => 12,
                'category_slug' => 'vr-ochki',
            ],
            [
                'name' => 'Meta Quest 3 (512GB)',
                'slug' => 'meta-quest-3-512',
                'description' => 'Версия с увеличенной памятью для большой библиотеки игр. Отличный выбор для активного VR и смешанной реальности.',
                'price' => 69990,
                'image' => 'Meta_Quest_3_128GB.jpeg',
                'stock' => 8,
                'category_slug' => 'vr-ochki',
            ],
            [
                'name' => 'Meta Quest 3S (128GB)',
                'slug' => 'meta-quest-3s-128',
                'description' => 'Доступный вход в современный VR: быстрый запуск, удобная настройка и большой каталог приложений.',
                'price' => 44990,
                'image' => 'Meta_Quest_3S_128GB.jpeg',
                'stock' => 15,
                'category_slug' => 'vr-ochki',
            ],
            [
                'name' => 'Meta Quest 2 (128GB)',
                'slug' => 'meta-quest-2-128',
                'description' => 'Проверенная классика: огромная библиотека, простая настройка и хорошее соотношение цена/возможности.',
                'price' => 29990,
                'image' => 'Meta_Quest_2_128GB.jpeg',
                'stock' => 10,
                'category_slug' => 'vr-ochki',
            ],
            [
                'name' => 'PICO 4 (128GB)',
                'slug' => 'pico-4-128',
                'description' => 'Лёгкий VR-шлем с хорошим балансом веса и удобной посадкой. Подойдёт для долгих сессий и мультимедиа.',
                'price' => 37990,
                'image' => 'PICO_4_128GB.jpeg',
                'stock' => 9,
                'category_slug' => 'vr-ochki',
            ],
            [
                'name' => 'HTC Vive XR Elite',
                'slug' => 'htc-vive-xr-elite',
                'description' => 'Компактный XR-шлем для VR и MR. Подходит для работы, презентаций и премиального пользовательского опыта.',
                'price' => 119990,
                'image' => 'HTC_Vive_XR_Elite.jpeg',
                'stock' => 4,
                'category_slug' => 'vr-ochki',
            ],
            [
                'name' => 'HTC Vive Pro 2',
                'slug' => 'htc-vive-pro-2',
                'description' => 'Профессиональная VR-система с 5K-разрешением, 120 Гц и точным Lighthouse-трекингом. Отлично для симуляторов и студий.',
                'price' => 139990,
                'image' => 'HTC_Vive_Pro_2.jpeg',
                'stock' => 3,
                'category_slug' => 'vr-ochki',
            ],
            [
                'name' => 'Valve Index (Full Kit)',
                'slug' => 'valve-index-full-kit',
                'description' => 'Премиальная PCVR-система: высокое качество трекинга, отличные контроллеры и плавная картинка. Для требовательных игроков.',
                'price' => 169990,
                'image' => 'Valve_Index_FullKit.jpeg',
                'stock' => 2,
                'category_slug' => 'vr-ochki',
            ],
            [
                'name' => 'PlayStation VR2',
                'slug' => 'ps-vr2',
                'description' => 'VR для PlayStation: OLED HDR, качественные контроллеры и удобная интеграция с консолью. Подходит для эксклюзивов и хитов.',
                'price' => 54990,
                'image' => 'PlayStation_VR2.jpeg',
                'stock' => 6,
                'category_slug' => 'vr-ochki',
            ],
            [
                'name' => 'Pimax Crystal (PCVR)',
                'slug' => 'pimax-crystal',
                'description' => 'Максимальная детализация и широкий обзор. Для симрейсинга, полетов и тех, кому важна «картинка как в жизни».',
                'price' => 239990,
                'image' => 'Pimax_Crystal_PCVR.jpeg',
                'stock' => 1,
                'category_slug' => 'vr-ochki',
            ],

            // Аксессуары
            [
                'name' => 'Набор контроллеров (универсальный)',
                'slug' => 'vr-controllers-universal',
                'description' => 'Пара эргономичных контроллеров с точной виброотдачей и удобными ремешками. Отлично для активных игр.',
                'price' => 12990,
                'image' => 'vr-controllers-universal.jpeg',
                'stock' => 30,
                'category_slug' => 'aksessuary',
            ],
            [
                'name' => 'Лицевой интерфейс (мягкий, вентиляция)',
                'slug' => 'face-interface-vent',
                'description' => 'Более комфортная посадка и меньше запотевания. Подходит для долгих игровых сессий и фитнеса.',
                'price' => 2990,
                'image' => 'face-interface-vent.jpeg',
                'stock' => 50,
                'category_slug' => 'aksessuary',
            ],
            [
                'name' => 'Защитные накладки на линзы (комплект)',
                'slug' => 'lens-protector-kit',
                'description' => 'Защищают оптику от царапин при хранении и переноске. Полезно, если используете чехол или сумку.',
                'price' => 990,
                'image' => 'lens-protector-kit.jpeg',
                'stock' => 120,
                'category_slug' => 'aksessuary',
            ],
            [
                'name' => 'Салфетка из микрофибры для оптики',
                'slug' => 'microfiber-cloth',
                'description' => 'Безопасная очистка линз без разводов. Рекомендуется для регулярного ухода за оптикой.',
                'price' => 290,
                'image' => 'microfiber-cloth.jpeg',
                'stock' => 300,
                'category_slug' => 'aksessuary',
            ],

            // Крепления и ремни
            [
                'name' => 'Элит-ремень с поворотной регулировкой',
                'slug' => 'elite-strap-dial',
                'description' => 'Улучшает фиксацию, снимает нагрузку с лица и позволяет быстро подстроить посадку одной рукой.',
                'price' => 6990,
                'image' => 'elite-strap-dial.jpeg',
                'stock' => 40,
                'category_slug' => 'straps',
            ],
            [
                'name' => 'Ремешки на руки для контроллеров (спортивные)',
                'slug' => 'controller-hand-straps-sport',
                'description' => 'Надёжная фиксация для активных игр: меньше риска уронить контроллер, больше свободы движений.',
                'price' => 1490,
                'image' => 'controller-hand-straps-sport.jpeg',
                'stock' => 80,
                'category_slug' => 'straps',
            ],
            [
                'name' => 'Баланс-груз на затылок (комфорт)',
                'slug' => 'counterweight-comfort',
                'description' => 'Сдвигает центр тяжести назад и делает шлем ощутимо комфортнее. Особенно полезно в долгих сессиях.',
                'price' => 2490,
                'image' => 'counterweight-comfort.jpeg',
                'stock' => 35,
                'category_slug' => 'straps',
            ],

            // Оптика и линзы
            [
                'name' => 'Линзы с диоптриями (индивидуальный набор)',
                'slug' => 'prescription-lenses-set',
                'description' => 'Комфорт без очков внутри шлема: меньше давления и бликов. Подбираются под ваш рецепт.',
                'price' => 8990,
                'image' => 'prescription-lenses-set.jpeg',
                'stock' => 25,
                'category_slug' => 'lenses',
            ],
            [
                'name' => 'Антибликовая плёнка на линзы (пара)',
                'slug' => 'anti-glare-film',
                'description' => 'Снижает блики и мелкие отражения, улучшая воспринимаемую контрастность изображения.',
                'price' => 1290,
                'image' => 'anti-glare-film.jpeg',
                'stock' => 70,
                'category_slug' => 'lenses',
            ],

            // Кабели и адаптеры
            [
                'name' => 'Кабель Link USB‑C 5м (для PCVR)',
                'slug' => 'link-cable-usbc-5m',
                'description' => 'Стабильное подключение шлема к ПК для SteamVR: высокая пропускная способность и удобная длина.',
                'price' => 2990,
                'image' => 'link-cable-usbc-5m.jpeg',
                'stock' => 60,
                'category_slug' => 'cables',
            ],
            [
                'name' => 'Адаптер USB‑C → USB‑A (быстрый)',
                'slug' => 'usbc-to-usba-adapter',
                'description' => 'Подходит для подключения к ПК и зарядки. Компактный, с хорошим контактом и надёжным корпусом.',
                'price' => 590,
                'image' => 'usbc-to-usba-adapter.jpeg',
                'stock' => 150,
                'category_slug' => 'cables',
            ],
            [
                'name' => 'Зарядный кабель USB‑C 2м (усиленный)',
                'slug' => 'usb-c-cable-2m',
                'description' => 'Плотная оплётка и усиленные коннекторы. Удобно для зарядки и подключения аксессуаров.',
                'price' => 790,
                'image' => 'usb-c-cable-2m.jpeg',
                'stock' => 200,
                'category_slug' => 'cables',
            ],

            // Трекинг и базы
            [
                'name' => 'Base Station 2.0 (база трекинга)',
                'slug' => 'base-station-2',
                'description' => 'Точная база для Lighthouse-трекинга. Рекомендуется для VR-комнат и максимально стабильного отслеживания.',
                'price' => 21990,
                'image' => 'base-station-2.jpeg',
                'stock' => 10,
                'category_slug' => 'tracking',
            ],
            [
                'name' => 'VR‑трекеры (комплект 3 шт.)',
                'slug' => 'vr-trackers-3pack',
                'description' => 'Для full-body трекинга: ноги/пояс. Отлично подходит для VRChat и танцевальных приложений.',
                'price' => 49990,
                'image' => 'vr-trackers-3pack.jpeg',
                'stock' => 5,
                'category_slug' => 'tracking',
            ],

            // Аудио и микрофоны
            [
                'name' => 'VR‑наушники (низкая задержка)',
                'slug' => 'vr-headphones-low-latency',
                'description' => 'Чёткий звук и хорошая сцена для шутеров и ритм-игр. Комфортная посадка под длительные сессии.',
                'price' => 4990,
                'image' => 'vr-headphones-low-latency.jpeg',
                'stock' => 45,
                'category_slug' => 'audio',
            ],
            [
                'name' => 'Петличный микрофон для VR (мини)',
                'slug' => 'lavalier-mic-vr',
                'description' => 'Улучшает голос в VR-чатах и стриминге. Лёгкий и удобный, крепится на одежду.',
                'price' => 1490,
                'image' => 'lavalier-mic-vr.jpeg',
                'stock' => 90,
                'category_slug' => 'audio',
            ],

            // Сумки и хранение
            [
                'name' => 'Кейс для VR‑шлема (жёсткий, ударопрочный)',
                'slug' => 'hard-case-vr',
                'description' => 'Защита в дороге: жёсткий корпус, мягкие вставки и место для контроллеров/кабелей.',
                'price' => 3990,
                'image' => 'hard-case-vr.jpeg',
                'stock' => 35,
                'category_slug' => 'cases',
            ],
            [
                'name' => 'Сумка для аксессуаров (органайзер)',
                'slug' => 'accessories-organizer-bag',
                'description' => 'Порядок в кабелях, адаптерах и мелочах: карманы, резинки-держатели и молния по периметру.',
                'price' => 1690,
                'image' => 'accessories-organizer-bag.jpeg',
                'stock' => 70,
                'category_slug' => 'cases',
            ],

            // Игры для VR (цифровые ключи)
            [
                'name' => 'Beat Saber (VR‑ключ)',
                'slug' => 'beat-saber-vr-key',
                'description' => 'Легендарная ритм‑игра: идеально для старта и фитнеса. Цифровой ключ активации (регион зависит от платформы).',
                'price' => 1990,
                'image' => 'beat-saber-vr-key.jpeg',
                'stock' => 999,
                'category_slug' => 'vr-games',
            ],
            [
                'name' => 'Half‑Life: Alyx (VR‑ключ)',
                'slug' => 'half-life-alyx-vr-key',
                'description' => 'Один из лучших VR‑экшенов: сюжет, физика и атмосфера. Цифровой ключ для платформы SteamVR.',
                'price' => 2990,
                'image' => 'half-life-alyx-vr-key.jpeg',
                'stock' => 500,
                'category_slug' => 'vr-games',
            ],
            [
                'name' => 'SUPERHOT VR (ключ)',
                'slug' => 'superhot-vr-key',
                'description' => 'Время движется, когда движетесь вы. Отличная игра для демонстрации VR друзьям и новичкам.',
                'price' => 1490,
                'image' => 'superhot-vr-key.jpeg',
                'stock' => 500,
                'category_slug' => 'vr-games',
            ],
            [
                'name' => 'VR‑пак «Топ для старта» (3 игры)',
                'slug' => 'vr-starter-pack-3games',
                'description' => 'Набор ключей: ритм + экшен + «релакс». Хороший стартовый комплект для первого месяца в VR.',
                'price' => 3990,
                'image' => 'vr-starter-pack-3games.jpeg',
                'stock' => 300,
                'category_slug' => 'vr-games',
            ],
        ];

        foreach ($products as $p) {
            $category = $categories[$p['category_slug']] ?? null;
            if (!$category) {
                continue;
            }

            Product::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'name' => $p['name'],
                    'description' => $p['description'],
                    'price' => $p['price'],
                    'image' => $p['image'],
                    'stock' => $p['stock'],
                    'category_id' => $category->id,
                ]
            );
        }
    }
}
