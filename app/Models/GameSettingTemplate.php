<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSettingTemplate extends Model
{
    use HasFactory;

    public const PVP_MODE = 'pvp';
    public const TVT_MODE = 'tvt';

    public const BLUE_SIDE = 'blue';
    public const RED_SIDE = 'red';

    public static function isBlueSide(string $side): bool
    {
        return self::BLUE_SIDE === $side;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'mode',
        'side',
        'side_change',
        'user_id',
    ];

    protected $guarded = [];
}
