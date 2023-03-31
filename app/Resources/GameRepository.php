<?php

namespace App\Resources;

use App\Http\Controllers\Game\GamePoints;
use App\Models\Game;
use App\Models\GameUser;
use App\Models\Round;
use App\Models\RoundScore;
use App\Models\UserRating;
use Illuminate\Support\Arr;

class GameRepository
{
    public function getLastGames()
    {
        return Game::limit(10)
            ->orderByDesc('id')
            ->with(['users', 'rounds'])
            ->get();
    }

    public function store(array $data): void
    {
        $datetime = (new \DateTimeImmutable())->setTimestamp($data['dateTime'])->format('Y-m-d H:i:s');

        $game = Game::create([
            'winner' => $data['game_winner_id'],
            'totalTime' => $data['total_time'],
            'gameSettingTemplateId' => $data['template_id'],
            'dateTime' => $datetime,
        ]);

        $round = Arr::first($data['rounds']);

        $gamers = $round['gamers'];

        $users = [];
        foreach ($gamers as $gamer) {
            GameUser::create([
                'game_id' => $game->id,
                'user_id' => $gamer['user_id']
            ]);
            $users[] = $gamer['user_id'];
        }

        $this->updateUserRating($users, (int) $data['game_winner_id']);

        $number = 1;
        foreach ($data['rounds'] as $round) {
            $createdRound = Round::create([
                'number' => $number,
                'winner' => $round['winner_id'],
                'totalTime' => 0,
                'game_id' => $game->id,
            ]);

            foreach ($round['gamers'] as $gamer) {
                RoundScore::create([
                    'round_id' => $createdRound->id,
                    'user_id' => $gamer['user_id'],
                    'score' => $gamer['score']
                ]);
            }

            $number++;
        }
    }

    public function updateUserRating(array $users, int $winnerUserId): void
    {
        $userRatings = [];

        foreach ($users as $userId) {
            $userRating = UserRating::where('user_id', $userId)->first();
            $userRatings[] = $userRating?->rating ?? GamePoints::START_POINTS;
        }


        $points = GamePoints::scorePoints(
            Arr::first($userRatings),
            Arr::last($userRatings),
            Arr::first($users) === $winnerUserId ? 1 : 2
        );

        foreach ($users as $key => $userId) {
            UserRating::updateOrCreate(
                ['user_id' => $userId],
                ['rating' => $points[$key]]
            );
        }
    }
}
