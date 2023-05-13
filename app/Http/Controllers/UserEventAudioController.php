<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserEventAudioRequest;
use App\Http\Resources\UserAudioEventResource;
use App\Models\Events;
use App\Models\User;
use App\Models\UserAudioEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserEventAudioController extends Controller
{
    public function store(UserEventAudioRequest $request): Response
    {
        $validated = $request->validated();

        $parameters = [];
        foreach ($validated['parameters'] as $parameter) {
            $parameters[$parameter['parameter']] = $parameter['parameter'] === 'winner' ?
                1 : $parameter['value'];
        }

        $user = auth()->user();

        $file = $request->file('file');

        $path = $file->store('audio_events');

        $name = $file->getClientOriginalName();

        UserAudioEvent::create([
            'user_id' => $user->id,
            'event_id' => $validated['event'],
            'path' => $path,
            'audio_name' => $name,
            'parameters' => $parameters
        ]);

        return response(status: 201);
    }
    public function index(User $user): JsonResource
    {
        return UserAudioEventResource::collection($user->eventAudios->sortByDesc(function ($eventAudio) {
            return count($eventAudio->parameters);
        }));
    }

    public function destroy(UserAudioEvent $audio): Response
    {
        $audio->delete();
        return response(status: 200);
    }
}
