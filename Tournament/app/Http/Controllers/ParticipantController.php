<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $myPlayer = Participant::firstOrCreate(
            ['user_id' => $user->id, 'type' => 'individual'],
            ['name' => $user->name]
        );
        $myPlayer->loadCount('tournaments');

        $myTeams = Participant::query()
            ->where('type', 'team')
            ->where('user_id', $user->id)
            ->withCount('tournaments')
            ->withCount('teamMembers')
            ->orderByDesc('id')
            ->get();

        return view('participants.index', compact('myPlayer', 'myTeams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Participant::create([
            'name' => $validated['name'],
            'type' => 'team',
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Команда создана');
    }

    public function update(Request $request, string $id)
    {
        $participant = Participant::findOrFail($id);

        if (!$participant->user_id || $participant->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate(['name' => 'required|string|max:255']);
        $participant->update($validated);

        return redirect()->back()->with('success', 'Данные участника обновлены');
    }

    public function destroy(string $id)
    {
        $participant = Participant::findOrFail($id);

        if (!$participant->user_id || $participant->user_id !== Auth::id()) {
            abort(403);
        }

        $participant->delete();
        return redirect()->back()->with('success', 'Участник удалён');
    }

    public function registerToTournament(Request $request, Tournament $tournament)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($tournament->status !== 'draft') {
            return back()->with('error', 'Регистрация доступна только на турниры в статусе "подготовка"');
        }

        $isManager = $tournament->canBeManagedBy(Auth::user());
        $validated = $request->validate([
            'participant_id' => 'nullable|exists:participants,id',
            'participant_ref' => 'nullable|string|max:255',
        ]);

        $participant = null;

        if ($isManager) {
            if (!empty($validated['participant_id'])) {
                $participant = Participant::find($validated['participant_id']);
            }

            if (!$participant && !empty($validated['participant_ref'])) {
                $ref = trim($validated['participant_ref']);

                if (ctype_digit($ref)) {
                    $participant = Participant::find((int) $ref);
                } else {
                    $participant = Participant::whereRaw('LOWER(name) = ?', [mb_strtolower($ref)])->first();
                }

                if (!$participant) {
                    $user = User::whereRaw('LOWER(public_id) = ?', [mb_strtolower($ref)])
                        ->orWhereRaw('LOWER(name) = ?', [mb_strtolower($ref)])
                        ->orWhereRaw('LOWER(email) = ?', [mb_strtolower($ref)])
                        ->first();

                    if ($user) {
                        $participant = Participant::firstOrCreate(
                            ['user_id' => $user->id, 'type' => 'individual'],
                            ['name' => $user->name]
                        );
                    }
                }
            }
        } else {
            $participantId = $validated['participant_id'] ?? null;

            if ($participantId) {
                $participant = Participant::query()
                    ->where('id', $participantId)
                    ->where('user_id', Auth::id())
                    ->first();
            } else {
                $participantType = $tournament->is_team_based ? 'team' : 'individual';
                $participant = Participant::query()
                    ->where('user_id', Auth::id())
                    ->where('type', $participantType)
                    ->orderBy('id')
                    ->first();
            }

            if (!$participant) {
                return back()->with('error', 'Нет доступного участника для регистрации. Создай игрока/команду в разделе "Участники".');
            }
        }

        if (!$participant) {
            return back()->with('error', 'Участник не найден. Укажи ID или точное имя.');
        }

        if (!$isManager) {
            $requiredType = $tournament->is_team_based ? 'team' : 'individual';
            if ($participant->type !== $requiredType) {
                return back()->with('error', 'Тип участника не подходит для формата этого турнира');
            }
        }

        $exists = TournamentParticipant::where([
            'tournament_id' => $tournament->id,
            'participant_id' => $participant->id,
        ])->exists();

        if ($exists) {
            return back()->with('error', 'Уже зарегистрирован');
        }

        if ($tournament->max_teams && $tournament->participants()->count() >= $tournament->max_teams) {
            return back()->with('error', 'Достигнут лимит участников турнира');
        }

        TournamentParticipant::create([
            'tournament_id' => $tournament->id,
            'participant_id' => $participant->id,
            'registered_at' => now(),
            'seed' => $tournament->participants()->count() + 1,
            'status' => 'active',
        ]);

        return back()->with('success', 'Заявка подтверждена: участник зарегистрирован в турнире');
    }
}
