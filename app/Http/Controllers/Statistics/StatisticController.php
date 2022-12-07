<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Round;
use App\Models\User;
use App\Models\UserRating;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $params['tableData'] = $this->prepareStatisticData();
        return view('statistic.index', $params);
    }

    private function prepareStatisticData()
    {
        $users = $this->users();
        $gamesParams = $this->getGamesParams();
        $roundParams = $this->getRoundParams();
        $ratingParams = $this->getRatingParams();
        $tableData = [];
        foreach ($users as $user) {
            $temp = [];
            $temp['id'] = $user['id'];
            $temp['login'] = $user['login'];
            $temp['win_count'] = $gamesParams['winner'][$user['id']] ?? 0;
            $temp['count_games'] = $gamesParams['count_games'][$user['id']] ?? 0;
            $temp['lose_count'] = $temp['count_games'] - $temp['win_count'];
            $temp['count_goals'] = $roundParams['count_goals'][$user['id']] ?? 0;
            $temp['missed_goals'] = $roundParams['missed_goals'][$user['id']] ?? 0;
            $temp['total_time'] = $this->formatTime($gamesParams['total_time'][$user['id']] ?? 0);
            $temp['rating'] = (int) ($ratingParams[$user['id']] ?? 0);
            $tableData[] = $temp;
        }
        usort($tableData, function ($a, $b) {
            return $b['rating'] <=> $a['rating'];
        });
        return $tableData;
    }

    private function users()
    {
        $users = User::all()?->toArray();
        return $users;
    }

    private function getRoundParams()
    {
        $result = [];
        $rounds = Round::all()?->toArray();
        foreach ($rounds as $round) {
            $result['count_goals'][$round['gamerOne']] = $this->increment(
                $result['count_goals'][$round['gamerOne']] ?? 0,
                $round['countOne'] ?? 0,
            );
            $result['count_goals'][$round['gamerTwo']] = $this->increment(
                $result['count_goals'][$round['gamerTwo']] ?? 0,
                $round['countTwo'] ?? 0,
            );
            $result['missed_goals'][$round['gamerOne']] = $this->increment(
                $result['missed_goals'][$round['gamerOne']] ?? 0,
                $round['countTwo'] ?? 0,
            );
            $result['missed_goals'][$round['gamerTwo']] = $this->increment(
                $result['missed_goals'][$round['gamerTwo']] ?? 0,
                $round['countOne'] ?? 0,
            );
        }
        return $result;
    }

    private function getGamesParams()
    {
        $result = [];
        $games = Game::all()?->toArray();
        foreach ($games as $game) {
            $result['count_games'][$game['gamerOne']] = $this->increment($result['count_games'][$game['gamerOne']] ?? 0);
            $result['count_games'][$game['gamerTwo']] = $this->increment($result['count_games'][$game['gamerTwo']] ?? 0);
            $result['winner'][$game['winner']] = $this->increment($result['winner'][$game['winner']] ?? 0);
            $result['total_time'][$game['gamerOne']] = $this->increment($result['total_time'][$game['gamerOne']] ?? 0, $game['totalTime'] ?? 0);
            $result['total_time'][$game['gamerTwo']] = $this->increment($result['total_time'][$game['gamerTwo']] ?? 0, $game['totalTime'] ?? 0);
        }
        return $result;
    }

    private function getRatingParams()
    {
        $ratings = UserRating::all()?->toArray();
        $result = [];
        foreach ($ratings as $rating) {
            $result[$rating['user_id']] = $rating['rating'];
        }
        return $result;
    }

    private function increment($value, $increment = 1)
    {
        return array_sum([$value, $increment]);
    }

    private function formatTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $seconds %= 3600;
        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }
}
