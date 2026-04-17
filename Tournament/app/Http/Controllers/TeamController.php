<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $team = Participant::create([
            'name' => $validated['name'],
            'type' => 'team',
            'user_id' => null,
        ]);

        return redirect()->route('participants.index')->with('success', 'Команда создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $team->load('teamMembers.user');
        return view('teams.show', compact('team'));
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

    public function addMember(Request $request, Participant $team)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if ($team->type !== 'team') {
            return back()->with('error', 'Не является командой');
        }

        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => $validated['user_id'],
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Игрок добавлен');
    }

    public function removeMember(TeamMember $member)
    {
        $member->delete();
        return back()->with('success', 'Игрок удалён');
    }
}
