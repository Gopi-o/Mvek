<?php

namespace App\Http\Controllers;

use App\Models\TournamentMatch;
use App\Models\Round;
use App\Models\MatchParticipant;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentMatchController extends Controller
{
    public function index(Tournament $tournament)
    {
        $matches = TournamentMatch::whereHas('round', function ($q) use ($tournament) {
                $q->where('tournament_id', $tournament->id);
            })
            ->with(['winner', 'matchParticipants.participant', 'round'])
            ->orderBy('round_id')
            ->orderBy('match_number')
            ->get();

        return view('matches.index', compact('tournament', 'matches'));
    }

    public function show(TournamentMatch $match)
    {
        $match->load(['round.tournament', 'matchParticipants.participant']);
        return view('matches.show', compact('match'));
    }

    public function updateResult(Request $request, TournamentMatch $match)
    {
        $tournament = $match->round->tournament;
        if (!$tournament->canBeManagedBy(Auth::user())) {
            abort(403);
        }

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

        if (($tournament->type?->slug ?? '') === 'single_elimination') {
            $this->advanceWinner($match);
        }

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
        $tournament = $match->round->tournament;
        if (!$tournament->canBeManagedBy(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'scheduled_at' => 'required|date',
        ]);

        $match->update(['scheduled_at' => $validated['scheduled_at']]);
        return back()->with('success', 'Время назначено');
    }

}
