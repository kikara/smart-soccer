<?php

namespace App\Http\Controllers\Game;

use \App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameRound;
use App\Models\GameSettingTemplate;
use App\Models\Round;
use App\Models\TableOccupation;
use App\Models\User;
use App\Models\UserAudioEvent;
use App\Models\UserSingleAudio;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        return view('game', $this->getLastGames());
    }

    public function getMainLayout()
    {
        $params['data'] = request()?->all();
        $params['data']['blue_gamer_name'] = User::find($params['data']['round']['blue_gamer_id'])->toArray();
        $params['data']['red_gamer_name'] = User::find($params['data']['round']['red_gamer_id'])->toArray();
        $params['data']['rounds_count'] = $this->countRounds($params['data']);
        return view('layouts.game_layout', $params);
    }

    public function getMainInfo()
    {
        $data = request()?->all();
        $data['blue_gamer_name'] = User::find($data['blue_gamer_id'])->toArray();
        $data['red_gamer_name'] = User::find($data['red_gamer_id'])->toArray();
        return $data;
    }

    public function getNewTableLayout()
    {
        $params = $this->getLastGames();
        return view('game.new_table', $params);
    }

    private function getLastGames(): array
    {
        $games = Game::limit(10)->orderBy('id', 'DESC')->get();
        if (! $games) {
            return ['games' => []];
        }

        $usersId = [];
        foreach ($games as $game) {
            array_push($usersId, $game->gamerOne, $game->gamerTwo);
        }
        $users = $this->getUsers($usersId);

        $rounds = $this->getGameRounds($games->modelKeys());
        $gameRounds = [];
        foreach ($rounds as $gameRound) {
            $gameRounds[$gameRound->gameId][] = $gameRound->toArray();
        }

        foreach ($games as $game) {
            $rounds = $gameRounds[$game->id];
            $account = GoalCount::getAccountOfGame($rounds);

            $game->gamerOneAccount = $account[$game->gamerOne] ?? 0;
            $game->gamerTwoAccount = $account[$game->gamerTwo] ?? 0;
            $game->gamerOneName = $users[$game->gamerOne]['login'];
            $game->gamerTwoName = $users[$game->gamerTwo]['login'];
            $game->totalTimeFormat = gmdate('H:i:s', $game->totalTime);
            $game->date = $this->formatDate($game->dateTime);
        }
        return ['games' => $games];
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

    public function getGamersAudio(Request $request)
    {
        $data = $request->all();
        $result['result'] = false;
        if (! empty($data['gamers'])) {
            $result['sounds'] = $this->getGamersSound($data['gamers']);
            $result['result'] = true;
            return $result;
        }
        return $result;
    }

    public function getRandomSounds($userId)
    {
        return UserSingleAudio::where('user_id', '=', $userId)
                ->select('path')
                ->get()?->toArray() ?? [];
    }

    public function getEventSounds($userId)
    {
        return UserAudioEvent::where('user_id', '=', $userId)
                ->leftJoin('events', 'user_audio_events.event_id', '=', 'events.id')
                ->select('user_audio_events.parameters', 'user_audio_events.path', 'events.code')
                ->get()?->toArray() ?? [];
    }

    public function getGamersSound(array $gamers)
    {
        $result = [];
        foreach ($gamers as $userId) {
            $result[$userId]['random_sounds'] = $this->getRandomSounds($userId);
            $result[$userId]['event_sounds'] = $this->getEventSounds($userId);
        }
        return $result;
    }

    private function getGameRounds(array $modelKeys)
    {
        return GameRound::leftJoin('rounds', 'game_rounds.roundId', '=', 'rounds.id')
            ->select(['rounds.*', 'game_rounds.gameId'])
            ->whereIn('game_rounds.gameId', $modelKeys)
            ->get();
    }

    private function formatDate(string $dateTime): string
    {
        try {
            return (new \DateTime($dateTime))->format('d.m.Y H:i');
        } catch (\Exception $exception) {
            return '00:00:00';
        }
    }
}
