<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    /** @use HasFactory<\Database\Factories\TeamMemberFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'guest_name',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Participant::class, 'team_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'Игрок';
    }
}
