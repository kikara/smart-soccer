<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatisticResource;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;

class StatisticController extends Controller
{
    public function index(): ResourceCollection
    {
        $games = Cache::remember('games', 3600, function () {
            return Game::all();
        });

        $users = Cache::remember('users', 3600, function () {
            return User::with(['gameUsers', 'ratings'])->get();
        });


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

            $user->rating = $user->ratings->isNotEmpty() ? $user->ratings->first()->rating : 0;

            return $user;
        });

        $users = $users->sortByDesc('rating');

        return StatisticResource::collection($users);
    }
}
