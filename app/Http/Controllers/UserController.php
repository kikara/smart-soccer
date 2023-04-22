<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Resources\GameRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;
use App\Http\Resources\SingleAudioResource;

class UserController extends Controller
{
    public function show(User $user): JsonResource
    {
        return new UserResource($user);
    }

    public function current(): JsonResource
    {
        $user = auth()->user();
        return new UserResource($user);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'avatar' => ['required', File::types(['png', 'jpg'])]
        ]);

        $path = $request->file('avatar')->store('avatars');

        $user = auth()->user();

        $user->avatar_path = $path;

        $user->save();
    }

    public function games(User $user, GameRepository $gameRepository)
    {
        return GameResource::collection($gameRepository->getUserLastGames($user));
    }
}
