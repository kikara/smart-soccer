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

    public function store(GameRequest $request, GameRepository $gameRepository): Response
    {
        $validated = $request->validated();

        $gameRepository->store($validated);

        TableOccupation::truncate();

        return response(status: 201);
    }
}
