<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participants = Participant::with('user')->paginate(20);
        return view('participants.index', compact('participants'));
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
            'type' => 'required|in:individual,team',
        ]);

        $participant = Participant::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Участник добавлен');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $this->authorize('update', $participant);
        
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $participant->update($validated);
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $participant);
        $participant->delete();
        return redirect()->back();
    }

    public function registerToTournament(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'participant_id' => 'required|exists:participants,id',
        ]);

        $exists = TournamentParticipant::where([
            'tournament_id' => $tournament->id,
            'participant_id' => $validated['participant_id'],
        ])->exists();

        if ($exists) {
            return back()->with('error', 'Уже зарегистрирован');
        }

        TournamentParticipant::create([
            'tournament_id' => $tournament->id,
            'participant_id' => $validated['participant_id'],
            'registered_at' => now(),
            'seed' => $tournament->participants()->count() + 1,
            'status' => 'active',
        ]);

        return back()->with('success', 'Зарегистрирован в турнире');
    }
}
