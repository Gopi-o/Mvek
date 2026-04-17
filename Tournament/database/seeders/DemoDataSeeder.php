<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Participant;
use App\Models\TournamentParticipant;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@tournament.gg'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $tournament = Tournament::firstOrCreate(
            ['name' => 'Тестовый турнир', 'owner_id' => $user->id],
            [
                'type_id' => 1,
                'status' => 'draft',
                'max_teams' => 8,
                'players_per_team' => 1,
            ]
        );

        if ($tournament->participants()->count() === 0) {
            $names = ['Игрок 1', 'Игрок 2', 'Игрок 3', 'Игрок 4'];
            foreach ($names as $i => $name) {
                $participant = Participant::create([
                    'name' => $name,
                    'type' => 'individual',
                ]);

                TournamentParticipant::create([
                    'tournament_id' => $tournament->id,
                    'participant_id' => $participant->id,
                    'registered_at' => now(),
                    'seed' => $i + 1,
                    'status' => 'active',
                ]);
            }
        }
    }
}
