<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    /** @use HasFactory<\Database\Factories\TournamentFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'type_id',
        'owner_id',
        'status',
        'max_teams',
        'players_per_team',
    ];

    public function type()
    {
        return $this->belongsTo(TournamentType::class, 'type_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function matches()
    {
        return $this->hasMany(TournamentMatch::class);
    }

    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'tournament_participants')
            ->using(TournamentParticipant::class)
            ->withPivot('registered_at', 'seed', 'status')
            ->withTimestamps();
    }
}
