<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TournamentParticipant extends Pivot
{
    /** @use HasFactory<\Database\Factories\TournamentParticipantFactory> */
    use HasFactory;

    protected $table = 'tournament_participants';

    protected $fillable = [
        'tournament_id',
        'participant_id',
        'registered_at',
        'seed',
        'status',
    ];


    protected $casts = [
        'registered_at' => 'date',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
