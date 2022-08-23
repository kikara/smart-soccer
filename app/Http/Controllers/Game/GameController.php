<?php

namespace App\Http\Controllers\Game;

use \App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameRound;
use App\Models\GameSettingTemplate;
use App\Models\Round;
use App\Models\TableOccupation;
use App\Models\User;

class GameController extends Controller
{
    public function index()
    {
        $params = $this->getLastGames();
        return view('game', $params);
    }

    public function getMainLayout()
    {
        $params['data'] = request()->all();
        $params['data']['blue_gamer_name'] = User::find($params['data']['round']['blue_gamer_id'])->toArray();
        $params['data']['red_gamer_name'] = User::find($params['data']['round']['red_gamer_id'])->toArray();
        $params['data']['rounds_count'] = $this->countRounds($params['data']);
        return view('layouts.game_layout', $params);
    }

    public function getMainInfo()
    {
        $data = request()->all();
        $data['blue_gamer_name'] = User::find($data['blue_gamer_id'])->toArray();
        $data['red_gamer_name'] = User::find($data['red_gamer_id'])->toArray();
        return $data;
    }

    public function getNewTableLayout()
    {
        $params = $this->getLastGames();
        return view('game.new_table', $params);
    }

    private function getLastGames()
    {
        $result['games'] = Game::limit(10)->orderBy('id', 'DESC')->get()?->toArray();
        if (! empty($result['games'])) {
            $result['games'] = array_reverse($result['games']);
        }
        $gamesID = [];
        $userID = [];
        foreach ($result['games'] as $game) {
            $userID[] = $game['gamerOne'];
            $userID[] = $game['gamerTwo'];
            $gamesID[] = $game['id'];
        }

        $gameRounds = GameRound::whereIn('gameId', $gamesID)->get()->toArray();
        $roundsID = [];
        foreach ($gameRounds as $gameRound) {
            $roundsID[] = $gameRound['roundId'];
        }

        $result['rounds'] = $this->getRounds($roundsID);
        $result['users'] = $this->getUsers($userID);

        foreach ($gameRounds as $gameRound) {
            $result['game_round'][$gameRound['gameId']][] = $result['rounds'][$gameRound['roundId']];
        }

        foreach ($result['games'] as $key => $game) {
            $rounds = $result['game_round'][$game['id']];
            $account = GoalCount::getAccountOfGame($rounds);
            $result['games'][$key]['account'] = $account;

            $gamerOneCount = $account[$game['gamerOne']] ?? 0;
            $gamerTwoCount = $account[$game['gamerTwo']] ?? 0;
            $accountStr = $gamerOneCount . ' : ' . $gamerTwoCount;
            $result['games'][$key]['accountStr'] = $accountStr;
            $result['games'][$key]['time'] = $this->secondsToArray($game['totalTime']);
            $result['games'][$key]['date'] = (new \DateTime($game['dateTime']))->format('d.m.Y H:i');
        }
        krsort($result['games']);
        return $result;
    }

    private function getRounds(array $ids)
    {
        $rounds = Round::whereIn('id', $ids)->get()->toArray();
        return $this->setKeys($rounds,'id');
    }

    private function setKeys($users, string $key)
    {
        $result = [];
        foreach ($users as $item) {
            $result[$item[$key]] = $item;
        }
        return $result;
    }

    private function getUsers(array $userID)
    {
        $users = User::whereIn('id', $userID)->get()->toArray();
        return $this->setKeys($users, 'id');
    }

    private function secondsToArray(int $seconds)
    {
        $res = [];
        $res['hours'] = floor($seconds / 3600);
        $seconds = $seconds % 3600;
        $res['minutes'] = floor($seconds / 60);
        $res['secs'] = $seconds % 60;
        return $res;
    }

    private function countRounds(array $data)
    {
        $result =  [];
        $result[$data['round']['blue_gamer_id']] = 0;
        $result[$data['round']['red_gamer_id']] = 0;
        foreach ($data['rounds'] as $round) {
            $winnerID = $round['winner_id'];
            if ($winnerID) {
                $result[$round['winner_id']] += 1;
            }
        }
        return $result;
    }
}
