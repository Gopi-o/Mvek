<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (!empty($user->public_id)) {
                return;
            }

            do {
                $publicId = strtoupper(Str::random(10));
            } while (self::where('public_id', $publicId)->exists());

            $user->public_id = $publicId;
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tournaments()
    {
        return $this->hasMany(Tournament::class, 'owner_id');
    }

    public function participant()
    {
        return $this->hasOne(Participant::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function managedTournaments()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_managers')
            ->withTimestamps();
    }
}
