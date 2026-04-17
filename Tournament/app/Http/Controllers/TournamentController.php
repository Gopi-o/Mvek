<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentType;
use App\Models\Participant;
use App\Models\Round;
use App\Models\TournamentMatch;
use App\Models\MatchParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tournaments = Tournament::with(['type', 'owner'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('tournaments.index', compact('tournaments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = TournamentType::all();
        return View('tournament.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:tournament_types,id',
            'max_teams' => 'nullable|integer|min:2',
            'players_per_team' => 'nullable|integer|min:1',
        ]);

        $tournament = Tournament::create([
            ...$validated,
            'owner_id' => Auth::id(),
            'status' => 'draft',
        ]);

        return redirect()->route('tournaments.show', $tournament);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tournament $tournament)
    {
        $tournament->load(['participants', 'rounds.matches.matchParticipants.participant']);
        return view('tournaments.show', compact('tournament'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tournament $tournament)
    {
        $this->authorize('update', $tournament);
        $types = TournamentType::all();
        return view('tournaments.edit', compact('tournament', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tournament $tournament)
    {
        $this->authorize('update', $tournament);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'in:draft,active,completed',
        ]);

        $tournament->update($validated);
        return redirect()->route('tournaments.show', $tournament);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tournament $tournament)
    {
        $this->authorize('delete', $tournament);
        $tournament->delete();
        return redirect()->route('tournaments.index');
    }

    public function generateBracket(Tournament $tournament)
    {
        $participants = $tournament->participants;
        $count = $participants->count();

        if ($count < 2) {
            return back()->with('error', 'Нужно минимум 2 участника');
        }

        $rounds = ceil(log($count, 2));
        $firstRoundMatches = pow(2, $rounds - 1);

        for ($i = 1; $i <= $rounds; $i++) {
            Round::create([
                'tournament_id' => $tournament->id,
                'round_number' => $i,
                'name' => $i == $rounds ? 'Финал' : ($i == $rounds - 1 ? 'Полуфинал' : "Раунд $i"),
            ]);
        }

        $firstRound = $tournament->rounds()->where('round_number', 1)->first();
        $participantsArray = $participants->shuffle()->values();

        for ($i = 0; $i < $firstRoundMatches; $i++) {
            $match = TournamentMatch::create([
                'round_id' => $firstRound->id,
                'match_number' => $i + 1,
                'status' => 'pending',
                'bracket_position' => $i + 1,
            ]);

            $p1 = $participantsArray[$i * 2] ?? null;
            $p2 = $participantsArray[$i * 2 + 1] ?? null;

            if ($p1) {
                MatchParticipant::create([
                    'match_id' => $match->id,
                    'participant_id' => $p1->id,
                    'is_winner' => false,
                ]);
            }
            if ($p2) {
                MatchParticipant::create([
                    'match_id' => $match->id,
                    'participant_id' => $p2->id,
                    'is_winner' => false,
                ]);
            }
        }

        for ($roundNum = 2; $roundNum <= $rounds; $roundNum++) {
            $round = $tournament->rounds()->where('round_number', $roundNum)->first();
            $matchesInRound = $firstRoundMatches / pow(2, $roundNum - 1);

            for ($i = 1; $i <= $matchesInRound; $i++) {
                TournamentMatch::create([
                    'round_id' => $round->id,
                    'match_number' => $i,
                    'status' => 'pending',
                    'bracket_position' => $i,
                ]);
            }
        }

        $tournament->update(['status' => 'active']);
        return redirect()->route('tournaments.show', $tournament)->with('success', 'Сетка сгенерирована');
    }

    public function start(Tournament $tournament)
    {
        if ($tournament->participants->count() < 2) {
            return back()->with('error', 'Недостаточно участников');
        }

        $tournament->update(['status' => 'active']);
        return redirect()->route('tournaments.show', $tournament);
    }

}