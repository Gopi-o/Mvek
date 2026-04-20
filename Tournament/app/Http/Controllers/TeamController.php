<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $team = Participant::create([
            'name' => $validated['name'],
            'type' => 'team',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('teams.show', $team)->with('success', 'Команда создана');
    }

    public function show(Participant $team)
    {
        $this->ensureTeamOwner($team);

        $team->load('teamMembers.user');
        return view('teams.show', compact('team'));
    }

    public function addMember(Request $request, Participant $team)
    {
        $this->ensureTeamOwner($team);

        $validated = $request->validate([
            'guest_name' => 'nullable|string|max:255|required_without:public_id',
            'public_id' => 'nullable|string|max:32|required_without:guest_name',
        ]);

        $publicId = trim((string) ($validated['public_id'] ?? ''));
        $guestName = trim((string) ($validated['guest_name'] ?? ''));

        if ($publicId !== '') {
            $user = User::whereRaw('LOWER(public_id) = ?', [mb_strtolower($publicId)])->first();
            if (!$user) {
                return back()->with('error', 'Пользователь с таким публичным ID не найден');
            }

            $exists = TeamMember::where('team_id', $team->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Этот пользователь уже в составе команды');
            }

            TeamMember::create([
                'team_id' => $team->id,
                'user_id' => $user->id,
                'guest_name' => null,
                'joined_at' => now(),
            ]);

            return back()->with('success', 'Игрок с аккаунтом добавлен в команду');
        }

        if ($guestName === '') {
            return back()->with('error', 'Укажи ник игрока');
        }

        $exists = TeamMember::where('team_id', $team->id)
            ->whereRaw('LOWER(guest_name) = ?', [mb_strtolower($guestName)])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Игрок с таким ником уже добавлен в эту команду');
        }

        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => null,
            'guest_name' => $guestName,
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Игрок добавлен');
    }

    public function removeMember(Request $request, Participant $team)
    {
        $this->ensureTeamOwner($team);

        $validated = $request->validate([
            'member_id' => 'required|exists:team_members,id',
        ]);

        $member = TeamMember::where('id', $validated['member_id'])
            ->where('team_id', $team->id)
            ->firstOrFail();

        $member->delete();
        return back()->with('success', 'Игрок удалён');
    }

    protected function ensureTeamOwner(Participant $team): void
    {
        if ($team->type !== 'team') {
            abort(404);
        }

        if ($team->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
