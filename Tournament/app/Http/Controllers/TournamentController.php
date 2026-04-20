<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use App\Models\Tournament;
use App\Models\TournamentType;
use App\Models\Participant;
use App\Models\Round;
use App\Models\TournamentMatch;
use App\Models\MatchParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tournament::with(['type', 'owner', 'participants', 'rounds']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhereHas('owner', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('format')) {
            $query->whereHas('type', fn($q) => $q->where('is_team_based', $request->format === 'team'));
        }

        $tournaments = $query->orderBy('created_at', 'desc')->paginate(12);

        $myTournaments = null;
        if (auth()->check()) {
            $myTournaments = Tournament::with(['participants', 'rounds'])
                ->where('owner_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('tournaments.index', compact('tournaments', 'myTournaments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = TournamentType::all();
        return view('tournaments.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'discipline' => 'required|string|max:100',
            'type_id' => 'required|exists:tournament_types,id',
            'max_teams' => 'nullable|integer|min:2',
            'players_per_team' => 'required|integer|min:1',
            'category' => 'nullable|in:gaming,non-gaming',
        ]);

        $tournament = Tournament::create([
            'name' => $validated['name'],
            'discipline' => $validated['discipline'],
            'type_id' => $validated['type_id'],
            'max_teams' => $validated['max_teams'],
            'players_per_team' => $validated['players_per_team'],
            'is_team_based' => $validated['players_per_team'] > 1,
            'category' => $validated['category'] ?? 'gaming',
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
        $tournament->load(['owner', 'managers', 'participants', 'rounds.matches.winner', 'rounds.matches.matchParticipants.participant']);
        $canManageTournament = $tournament->canBeManagedBy(Auth::user());
        $availableSelfParticipants = collect();

        if (Auth::check()) {
            $availableSelfParticipants = Participant::query()
                ->where('user_id', Auth::id())
                ->when($tournament->is_team_based, fn ($q) => $q->where('type', 'team'))
                ->when(!$tournament->is_team_based, fn ($q) => $q->where('type', 'individual'))
                ->orderBy('name')
                ->get();
        }

        return view('tournaments.show', compact('tournament', 'canManageTournament', 'availableSelfParticipants'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tournament $tournament)
    {
        if (!$tournament->canBeManagedBy(Auth::user())) {
            abort(403);
        }
        $types = TournamentType::all();
        return view('tournaments.edit', compact('tournament', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tournament $tournament)
    {
        if (!$tournament->canBeManagedBy(Auth::user())) {
            abort(403);
        }
        
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
        if ($tournament->owner_id !== Auth::id()) {
            abort(403);
        }
        $tournament->delete();
        return redirect()->route('tournaments.index');
    }

    

    public function generateBracket(Tournament $tournament)
    {
        $tournament->load(['type', 'participants']);

        if (!$tournament->canBeManagedBy(Auth::user())) {
            abort(403);
        }

        $participants = $tournament->participants;
        $count = $participants->count();

        if ($count < 2) {
            return back()->with('error', 'Нужно минимум 2 участника');
        }

        TournamentMatch::whereHas('round', fn ($q) => $q->where('tournament_id', $tournament->id))->delete();
        $tournament->rounds()->delete();

        $participants = $participants->shuffle()->values();
        $typeSlug = $tournament->type?->slug ?? 'single_elimination';

        match ($typeSlug) {
            'round_robin' => $this->generateRoundRobin($tournament, $participants),
            'double_elimination' => $this->generateDoubleElimination($tournament, $participants),
            default => $this->generateSingleElimination($tournament, $participants),
        };

        $tournament->update(['status' => 'active']);
        return redirect()->route('tournaments.show', $tournament)->with('success', 'Сетка сгенерирована для выбранного формата');
    }

    protected function generateSingleElimination(Tournament $tournament, $participants): void
    {
        $count = $participants->count();
        $roundsCount = (int) ceil(log($count, 2));
        $firstRoundMatches = (int) (2 ** ($roundsCount - 1));

        for ($i = 1; $i <= $roundsCount; $i++) {
            $round = Round::create([
                'tournament_id' => $tournament->id,
                'round_number' => $i,
                'name' => $this->singleEliminationRoundName($i, $roundsCount),
            ]);

            $matchesInRound = (int) ($firstRoundMatches / (2 ** ($i - 1)));
            for ($matchNumber = 1; $matchNumber <= $matchesInRound; $matchNumber++) {
                $match = TournamentMatch::create([
                    'round_id' => $round->id,
                    'match_number' => $matchNumber,
                    'status' => 'pending',
                    'bracket_position' => $matchNumber,
                ]);

                if ($i === 1) {
                    $p1 = $participants[($matchNumber - 1) * 2] ?? null;
                    $p2 = $participants[($matchNumber - 1) * 2 + 1] ?? null;
                    $this->attachParticipantsToMatch($match, $p1?->id, $p2?->id);
                }
            }
        }
    }

    protected function generateRoundRobin(Tournament $tournament, $participants): void
    {
        $participants = $participants->values();

        if ($participants->count() % 2 !== 0) {
            $participants->push(null);
        }

        $total = $participants->count();
        $roundsCount = $total - 1;
        $half = (int) ($total / 2);
        $rotation = $participants->all();

        for ($roundIndex = 0; $roundIndex < $roundsCount; $roundIndex++) {
            $round = Round::create([
                'tournament_id' => $tournament->id,
                'round_number' => $roundIndex + 1,
                'name' => 'Тур ' . ($roundIndex + 1),
            ]);

            $matchNumber = 1;
            for ($i = 0; $i < $half; $i++) {
                $left = $rotation[$i];
                $right = $rotation[$total - 1 - $i];

                if ($left && $right) {
                    $match = TournamentMatch::create([
                        'round_id' => $round->id,
                        'match_number' => $matchNumber++,
                        'status' => 'pending',
                        'bracket_position' => $i + 1,
                    ]);

                    $this->attachParticipantsToMatch($match, $left->id, $right->id);
                }
            }

            $fixed = array_shift($rotation);
            $moved = array_pop($rotation);
            array_unshift($rotation, $fixed);
            array_splice($rotation, 1, 0, [$moved]);
        }
    }

    protected function generateDoubleElimination(Tournament $tournament, $participants): void
    {
        $count = $participants->count();
        $winnersRounds = (int) ceil(log($count, 2));
        $firstRoundMatches = (int) (2 ** ($winnersRounds - 1));
        $roundNumber = 1;

        for ($i = 1; $i <= $winnersRounds; $i++) {
            $round = Round::create([
                'tournament_id' => $tournament->id,
                'round_number' => $roundNumber++,
                'name' => 'Winners R' . $i,
            ]);

            $matchesInRound = (int) ($firstRoundMatches / (2 ** ($i - 1)));
            for ($matchNumber = 1; $matchNumber <= $matchesInRound; $matchNumber++) {
                $match = TournamentMatch::create([
                    'round_id' => $round->id,
                    'match_number' => $matchNumber,
                    'status' => 'pending',
                    'bracket_position' => $matchNumber,
                ]);

                if ($i === 1) {
                    $p1 = $participants[($matchNumber - 1) * 2] ?? null;
                    $p2 = $participants[($matchNumber - 1) * 2 + 1] ?? null;
                    $this->attachParticipantsToMatch($match, $p1?->id, $p2?->id);
                }
            }
        }

        $losersRounds = max(1, ($winnersRounds - 1) * 2);
        $baseMatches = max(1, (int) floor($firstRoundMatches / 2));

        for ($i = 1; $i <= $losersRounds; $i++) {
            $round = Round::create([
                'tournament_id' => $tournament->id,
                'round_number' => $roundNumber++,
                'name' => 'Losers R' . $i,
            ]);

            $matchesInRound = max(1, (int) floor($baseMatches / (2 ** max(0, intdiv($i - 1, 2)))));
            for ($matchNumber = 1; $matchNumber <= $matchesInRound; $matchNumber++) {
                TournamentMatch::create([
                    'round_id' => $round->id,
                    'match_number' => $matchNumber,
                    'status' => 'pending',
                    'bracket_position' => $matchNumber,
                ]);
            }
        }

        $grandFinal = Round::create([
            'tournament_id' => $tournament->id,
            'round_number' => $roundNumber,
            'name' => 'Grand Final',
        ]);

        TournamentMatch::create([
            'round_id' => $grandFinal->id,
            'match_number' => 1,
            'status' => 'pending',
            'bracket_position' => 1,
        ]);
    }

    protected function attachParticipantsToMatch(TournamentMatch $match, ?int $participantOneId, ?int $participantTwoId): void
    {
        if ($participantOneId) {
            MatchParticipant::create([
                'match_id' => $match->id,
                'participant_id' => $participantOneId,
                'is_winner' => false,
                'place' => 1,
            ]);
        }

        if ($participantTwoId) {
            MatchParticipant::create([
                'match_id' => $match->id,
                'participant_id' => $participantTwoId,
                'is_winner' => false,
                'place' => 2,
            ]);
        }
    }

    protected function singleEliminationRoundName(int $roundNumber, int $roundsCount): string
    {
        return match (true) {
            $roundNumber === $roundsCount => 'Финал',
            $roundNumber === $roundsCount - 1 => 'Полуфинал',
            $roundNumber === $roundsCount - 2 => 'Четвертьфинал',
            default => 'Раунд ' . $roundNumber,
        };
    }

    public function start(Tournament $tournament)
    {
        if (!$tournament->canBeManagedBy(Auth::user())) {
            abort(403);
        }

        if ($tournament->participants->count() < 2) {
            return back()->with('error', 'Недостаточно участников');
        }

        $tournament->update(['status' => 'active']);
        return redirect()->route('tournaments.show', $tournament);
    }

    public function addManager(Request $request, Tournament $tournament)
    {
        if ($tournament->owner_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'public_id' => 'required|string|max:12',
        ]);

        $user = User::whereRaw('LOWER(public_id) = ?', [mb_strtolower(trim($validated['public_id']))])->first();

        if (!$user) {
            return back()->with('error', 'Пользователь с таким ID не найден');
        }

        if ($user->id === $tournament->owner_id) {
            return back()->with('error', 'Создатель уже является главным организатором');
        }

        $tournament->managers()->syncWithoutDetaching([$user->id]);

        return back()->with('success', 'Со-организатор добавлен');
    }

    public function removeManager(Tournament $tournament, User $user)
    {
        if ($tournament->owner_id !== Auth::id()) {
            abort(403);
        }

        $tournament->managers()->detach($user->id);

        return back()->with('success', 'Со-организатор удалён');
    }

}