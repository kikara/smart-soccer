<?php

namespace App\Http\Controllers\Game;

use \App\Http\Controllers\Controller;
use App\Http\Requests\GameRequest;
use App\Http\Resources\GameResource;
use App\Models\TableOccupation;
use App\Models\User;
use App\Models\UserAudioEvent;
use App\Models\UserSingleAudio;
use App\Resources\GameRepository;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class GameController extends Controller
{
    public function index(): View
    {
        return view('index');
    }

    public function games(GameRepository $gameRepository): JsonResource
    {
        return GameResource::collection($gameRepository->getLastGames());
    }

    public function layout()
    {
        return view('layouts.game_layout');
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

    public function getNewTableLayout(GameRepository $gameRepository): View
    {
        return view('game.new_table', ['games' => $gameRepository->getLastGames()]);
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

    /**
     * @deprecated
     * @param Request $request
     * @return array
     */
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

    public function store(GameRequest $request, GameRepository $gameRepository): Response
    {
        /**
         * TODO delete
         */
        return response(201);
        $validated = $request->validated();

        $gameRepository->store($validated);

        TableOccupation::truncate();

        return response(status: 201);
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
