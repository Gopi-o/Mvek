<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Participant;
use App\Models\TournamentParticipant;
use App\Models\TournamentType;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@tournament.gg'],
            [
                'name' => 'Tournament Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $types = TournamentType::query()->pluck('id', 'slug');

        $tournaments = [
            [
                'name' => 'Кубок Весны CS2',
                'discipline' => 'Counter-Strike 2',
                'type_slug' => 'single_elimination',
                'max_teams' => 16,
                'players_per_team' => 5,
                'is_team_based' => true,
                'category' => 'gaming',
                'participants' => ['Arctic Wolves', 'Iron Titans', 'Pixel Storm', 'Omega Unit', 'Neon Squad', 'Final Bosses'],
            ],
            [
                'name' => 'Лига Шахмат Городов',
                'discipline' => 'Шахматы',
                'type_slug' => 'round_robin',
                'max_teams' => 10,
                'players_per_team' => 1,
                'is_team_based' => false,
                'category' => 'non-gaming',
                'participants' => ['Алиса Миронова', 'Иван Трофимов', 'Олег Панин', 'Мария Соколова', 'Денис Крылов', 'Наталья Егорова'],
            ],
            [
                'name' => 'Dota 2 Double Challenge',
                'discipline' => 'Dota 2',
                'type_slug' => 'double_elimination',
                'max_teams' => 8,
                'players_per_team' => 5,
                'is_team_based' => true,
                'category' => 'gaming',
                'participants' => ['Ancient Keepers', 'Void Hunters', 'Crimson Tide', 'Celestial Five', 'Shadow Lanterns', 'Radiant Pulse'],
            ],
        ];

        foreach ($tournaments as $tournamentData) {
            $typeId = $types[$tournamentData['type_slug']] ?? null;
            if (!$typeId) {
                continue;
            }

            $tournament = Tournament::updateOrCreate(
                ['name' => $tournamentData['name'], 'owner_id' => $admin->id],
                [
                    'discipline' => $tournamentData['discipline'],
                    'type_id' => $typeId,
                    'status' => 'draft',
                    'max_teams' => $tournamentData['max_teams'],
                    'players_per_team' => $tournamentData['players_per_team'],
                    'is_team_based' => $tournamentData['is_team_based'],
                    'category' => $tournamentData['category'],
                ]
            );

            foreach ($tournamentData['participants'] as $index => $name) {
                $participant = Participant::firstOrCreate(
                    ['name' => $name],
                    ['type' => $tournamentData['is_team_based'] ? 'team' : 'individual']
                );

                TournamentParticipant::updateOrCreate(
                    [
                        'tournament_id' => $tournament->id,
                        'participant_id' => $participant->id,
                    ],
                    [
                        'registered_at' => now(),
                        'seed' => $index + 1,
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
