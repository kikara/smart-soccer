<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Http\Resources\GameSettingTemplateResource;
use App\Http\Resources\UserResource;
use App\Models\GameSettingTemplate;
use App\Models\User;
use App\Resources\GameRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
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

    public function index(Request $request): ResourceCollection
    {
        $users = User::all();
        if ($chatId = $request->input('chat_id')) {
            $users = $users->filter(function ($user) use ($chatId) {
                return $user->telegram_chat_id !== $chatId;
            });
        }
        return UserResource::collection($users);
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

    public function games(User $user, GameRepository $gameRepository): ResourceCollection
    {
        return GameResource::collection($gameRepository->getUserLastGames($user));
    }

    public function find(Request $request): UserResource|Response
    {
        $validated = $request->validate(['chat_id' => 'required', 'string']);

        $user = User::where('telegram_chat_id', $validated['chat_id'])->first();

        return $user ? UserResource::make($user) : response(status: 404);
    }

    public function settings(User $user): JsonResponse|ResourceCollection
    {
        return $user->settings->isEmpty() ? response()->json(['result' => false]) : GameSettingTemplateResource::collection($user->settings);
    }
}
