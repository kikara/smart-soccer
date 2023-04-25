<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property $id
 * @property $login
 * @property $avatar_path string|nullable
 * @property Collection $audios
 * @property Collection $eventAudios
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'telegram_token',
        'name',
        'email',
        'password',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * `user_single_audio`
     */
    public function audios(): HasMany
    {
        return $this->hasMany(UserSingleAudio::class);
    }

    public function eventAudios(): HasMany
    {
        return $this->hasMany(UserAudioEvent::class);
    }

    public function gameUsers(): HasMany
    {
        return $this->hasMany(GameUser::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(UserRating::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(GameSettingTemplate::class);
    }
}
