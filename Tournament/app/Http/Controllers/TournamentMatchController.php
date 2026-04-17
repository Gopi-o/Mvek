<?php

namespace App\Http\Controllers;

use App\Models\TournamentMatch;
use App\Models\Round;
use App\Models\MatchParticipant;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentMatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Tournament $tournament)
    {
        $matches = TournamentMatch::whereHas('round', function ($q) use ($tournament) {
                $q->where('tournament_id', $tournament->id);
            })
            ->with(['matchParticipants.participant', 'round'])
            ->orderBy('round_id')
            ->orderBy('match_number')
            ->get();

        return view('matches.index', compact('tournament', 'matches'));
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
    public function show(TournamentMatch $match)
    {
        $match->load(['round.tournament', 'matchParticipants.participant']);
        return view('matches.show', compact('match'));
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

    public function updateResult(Request $request, TournamentMatch $match)
    {
        $validated = $request->validate([
            'winner_id' => 'required|exists:participants,id',
            'score' => 'nullable|string|max:50',
        ]);

        $match->matchParticipants()->update(['is_winner' => false]);

        $winner = $match->matchParticipants()
            ->where('participant_id', $validated['winner_id'])
            ->first();

        if ($winner) {
            $winner->update([
                'is_winner' => true,
                'score' => $validated['score'] ?? null,
            ]);
        }

        $match->update(['status' => 'completed']);

        $this->advanceWinner($match);

        return back()->with('success', 'Результат сохранён');
    }

    protected function advanceWinner(TournamentMatch $match)
    {
        $winner = $match->matchParticipants()->where('is_winner', true)->first();
        if (!$winner) return;

        $currentRound = $match->round;
        $nextRound = Round::where('tournament_id', $currentRound->tournament_id)
            ->where('round_number', $currentRound->round_number + 1)
            ->first();

        if (!$nextRound) return; 

        $nextMatchNumber = ceil($match->match_number / 2);
        $isFirstSlot = $match->match_number % 2 == 1;

        $nextMatch = TournamentMatch::firstOrCreate(
            [
                'round_id' => $nextRound->id,
                'match_number' => $nextMatchNumber,
            ],
            [
                'status' => 'pending',
                'bracket_position' => $nextMatchNumber,
            ]
        );

        $nextMatch->matchParticipants()
            ->where('place', $isFirstSlot ? 1 : 2)
            ->delete();

        MatchParticipant::create([
            'match_id' => $nextMatch->id,
            'participant_id' => $winner->participant_id,
            'is_winner' => false,
            'place' => $isFirstSlot ? 1 : 2,
        ]);
    }

    public function schedule(Request $request, TournamentMatch $match)
    {
        $validated = $request->validate([
            'scheduled_at' => 'required|date',
        ]);

        $match->update(['scheduled_at' => $validated['scheduled_at']]);
        return back()->with('success', 'Время назначено');
    }

    public function generateMatches(Tournament $tournament)
    {
        $rounds = $tournament->rounds()->orderBy('round_number')->get();
        $firstRound = $rounds->first();
        $participantCount = $tournament->participants()->count();
        $firstRoundMatches = 2 ** (ceil(log($participantCount, 2)) - 1);

        $participants = $tournament->participants->shuffle();
        for ($i = 0; $i < $firstRoundMatches; $i++) {
            $match = TournamentMatch::create([
                'round_id' => $firstRound->id,
                'match_number' => $i + 1,
                'status' => 'pending',
                'bracket_position' => $i + 1,
            ]);

            $p1 = $participants[$i * 2] ?? null;
            $p2 = $participants[$i * 2 + 1] ?? null;

            if ($p1) {
                MatchParticipant::create([
                    'match_id' => $match->id,
                    'participant_id' => $p1->id,
                    'is_winner' => false,
                    'place' => 1,
                ]);
            }
            if ($p2) {
                MatchParticipant::create([
                    'match_id' => $match->id,
                    'participant_id' => $p2->id,
                    'is_winner' => false,
                    'place' => 2,
                ]);
            }
        }

        foreach ($rounds->skip(1) as $round) {
            $matchesCount = $firstRoundMatches / (2 ** ($round->round_number - 1));
            for ($i = 1; $i <= $matchesCount; $i++) {
                TournamentMatch::create([
                    'round_id' => $round->id,
                    'match_number' => $i,
                    'status' => 'pending',
                    'bracket_position' => $i,
                ]);
            }
        }

        return true;
    }
}
