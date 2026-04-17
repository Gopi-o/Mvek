<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\MatchParticipantFactory> */
    use HasFactory;

    protected $fillable = [
        'match_id',
        'participant_id',
        'is_winner',
        'score',
        'place',
    ];

    protected $casts = [
        'is_winner' => 'boolean',
    ];

    public function match()
    {
        return $this->belongsTo(TournamentMatch::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
