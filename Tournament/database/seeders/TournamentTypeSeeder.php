<?php

namespace Database\Seeders;

use App\Models\TournamentType;
use Illuminate\Database\Seeder;

class TournamentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Single Elimination',
                'slug' => 'single_elimination',
                'description' => 'На выбывание, проигравший выбывает из турнира',
            ],
            [
                'name' => 'Double Elimination',
                'slug' => 'double_elimination',
                'description' => 'На выбывание с лузерс раундом, два поражения для вылета',
            ],
            [
                'name' => 'Round Robin',
                'slug' => 'round_robin',
                'description' => 'Каждый участник играет с каждым',
            ],
        ];

        foreach ($types as $type) {
            TournamentType::firstOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }
    }
}
