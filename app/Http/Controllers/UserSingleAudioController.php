<?php

namespace App\Http\Controllers;

use App\Http\Resources\SingleAudioResource;
use App\Models\User;
use App\Models\UserSingleAudio;
use App\Services\UserSingleAudioService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\File;

class UserSingleAudioController extends Controller
{
    public function __construct(
        protected UserSingleAudioService $service
    )
    {
    }

    public function store(Request $request): Response
    {
        $request->validate([
            'file' => ['required', File::types(['mp3', 'wmv', 'wav'])]
        ]);

        $this->service->store($request->file('file'));

        return response(status: 201);
    }

    public function index(User $user): ResourceCollection
    {
        return SingleAudioResource::collection($user->audios);
    }

    public function destroy(UserSingleAudio $audio): Response
    {
        $audio->delete();

        return response(status:200);
    }
}
