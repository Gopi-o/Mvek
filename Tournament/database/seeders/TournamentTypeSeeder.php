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
                'name' => 'Одиночный',
                'max_participants' => 32,
                'is_team_based' => false,
                'description' => 'Индивидуальные соревнования 1 на 1',
            ],
            [
                'name' => 'Командный',
                'max_participants' => 16,
                'is_team_based' => true,
                'description' => 'Командные соревнования',
            ],
        ];

        foreach ($types as $type) {
            TournamentType::create($type);
        }
    }
}
