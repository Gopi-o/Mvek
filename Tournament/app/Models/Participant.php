<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    /** @use HasFactory<\Database\Factories\ParticipantFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_participants')
            ->using(TournamentParticipant::class)
            ->withPivot('registered_at', 'seed', 'status')
            ->withTimestamps();
    }

    public function matchParticipant()
    {
        return $this->hasMany(MatchParticipant::class);
    }
}
