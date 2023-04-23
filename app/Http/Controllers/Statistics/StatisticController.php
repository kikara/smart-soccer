<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatisticResource;
use App\Models\Game;
use App\Models\Round;
use App\Models\User;
use App\Models\UserRating;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index(): ResourceCollection
    {
        $games = Game::all();

        $users = User::with(['gameUsers', 'ratings'])->get();

        $users = $users->map(function ($user) use ($games) {
            $user->win = 0;
            $user->lose = 0;
            $user->gameCount = 0;

            foreach ($user->gameUsers as $gameUser) {
                $game = $games->find($gameUser->game_id);

                if (! $game) {
                    continue;
                }

                ++$user->gameCount;

                if ($game->winner === $user->id) {
                    ++$user->win;
                } else {
                    ++$user->lose;
                }
            }

            return $user;
        });

        return StatisticResource::collection($users);
    }
}
