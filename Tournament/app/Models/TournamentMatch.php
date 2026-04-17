<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentMatch extends Model
{
    /** @use HasFactory<\Database\Factories\TournamentMatchFactory> */
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'round_id',
        'match_number',
        'status',
        'bracket_position',
        'scheduled_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function matchParticipant()
    {
        return $this->hasMany(MatchParticipant::class);
    }

    public function winner()
    {
        return $this->hasOne(MatchParticipant::class)->where('is_winner', true);
    }
}
