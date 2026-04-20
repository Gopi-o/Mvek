<?php

namespace App\Http\Controllers;

use App\Models\Round;
use App\Models\Tournament;

class RoundController extends Controller
{
    public function index(Tournament $tournament)
    {
        $rounds = $tournament->rounds()
            ->with('matches.winner', 'matches.matchParticipants.participant')
            ->orderBy('round_number')
            ->get();
        
        return view('rounds.index', compact('tournament', 'rounds'));
    }

    public function show(Round $round)
    {
        $round->load(['tournament', 'matches.winner', 'matches.matchParticipants.participant']);
        return view('rounds.show', compact('round'));
    }

}
