<?php

namespace App\Http\Controllers;

use App\Models\Round;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;

class RoundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Tournament $tournament)
    {
        $rounds = $tournament->rounds()
            ->with('matches.matchParticipants.participant')
            ->orderBy('round_number')
            ->get();
        
        return view('rounds.index', compact('tournament', 'rounds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Round $round)
    {
        $round->load(['tournament', 'matches.matchParticipants.participant']);
        return view('rounds.show', compact('round'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createRounds(Tournament $tournament)
    {
        $participantCount = $tournament->participants()->count();
        $roundCount = ceil(log($participantCount, 2));

        for ($i = 1; $i <= $roundCount; $i++) {
            $name = match ($i) {
                $roundCount => 'Финал',
                $roundCount - 1 => 'Полуфинал',
                default => "1/" . (2 ** ($roundCount - $i + 1)) . " финала",
            };

            Round::create([
                'tournament_id' => $tournament->id,
                'round_number' => $i,
                'name' => $name,
            ]);
        }

        return true;
    }
}
