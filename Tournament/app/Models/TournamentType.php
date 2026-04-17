<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentType extends Model
{
    /** @use HasFactory<\Database\Factories\TournamentTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'max_participants',
        'is_team_based',
        'description',
    ];

    protected $casts = [
        'is_team_based' => 'boolean',
    ];

    public function tournaments()
    {
        return $this->hasMany(Tournament::class, 'type_id');
    }
}
