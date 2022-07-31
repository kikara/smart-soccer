<?php

namespace App\Http\Controllers\Game;

use \App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameRound;
use App\Models\GameSettingTemplate;
use App\Models\Round;
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
        return view('layouts.game_layout', $params);
    }

    public function getMainInfo()
    {
        $data = request()->all();
        $data['blue_gamer_name'] = User::find($data['blue_gamer_id'])->toArray();
        $data['red_gamer_name'] = User::find($data['red_gamer_id'])->toArray();
        return $data;
    }

    public function saveGame()
    {
        $data = request()?->all();
        if (empty($data)) {
            return view('game.new_table');
        }
        $this->saveGameRounds($data);
        $params = $this->getLastGames();
        return view('game.new_table', $params);
    }

    private function saveGameRounds(array $data)
    {
        $rounds = $data['rounds'];
        $firstRound = $rounds[0];
        $dateTime = new \DateTime;
        $date = $dateTime->setTimestamp((int) $data['dateTime'])->format('Y-m-d H:i:s');

        $game = Game::create([
            'gamerOne' => $firstRound['blue_gamer_id'],
            'gamerTwo' => $firstRound['red_gamer_id'],
            'winner' => $data['game_winner_id'],
            'totalTime' => $data['total_time'],
            'gameSettingTemplateId' => $data['template_id'],
            'dateTime' => $date,
        ]);
        $gameID = $game->id;
        $number = 1;
        $isSideChange = $data['is_side_change'] === 'true';
        foreach ($data['rounds'] as $round) {
            if ($number == 2 && $isSideChange) {
                $gamerOne = $round['red_gamer_id'];
                $gamerTwo = $round['blue_gamer_id'];
                $countOne = $round['red_count'];
                $countTwo = $round['blue_count'];
            } else {
                $gamerOne = $round['blue_gamer_id'];
                $gamerTwo = $round['red_gamer_id'];
                $countOne = $round['blue_count'];
                $countTwo = $round['red_count'];
            }
            $roundModel = Round::create([
                'number' => $number,
                'gamerOne' => $gamerOne,
                'gamerTwo' => $gamerTwo,
                'countOne' => $countOne,
                'countTwo' => $countTwo,
                'winner' => $round['winner_id'],
                'totalTime' => 0,
            ]);

            GameRound::create([
                'gameId' => $gameID,
                'roundId' => $roundModel->id,
            ]);
            $number++;
        }
    }

    private function getLastGames()
    {
        $result['games'] = Game::limit(10)->get()->toArray();
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

    function secondsToArray(int $seconds)
    {
        $res = [];
        $res['hours'] = floor($seconds / 3600);
        $seconds = $seconds % 3600;
        $res['minutes'] = floor($seconds / 60);
        $res['secs'] = $seconds % 60;
        return $res;
    }
}
