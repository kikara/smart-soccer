<?php

namespace App\Services;

use App\Repositories\UserSingleAudioRepository;
use Illuminate\Http\UploadedFile;

class UserSingleAudioService
{
    public function __construct(
        protected UserSingleAudioRepository $repository
    )
    {
    }

    /**
     * @param UploadedFile $file
     * @return void
     */
    public function store(UploadedFile $file): void
    {
        $user = auth()->user();

        $name = $file->getClientOriginalName();

        $path = $file->store('user_single_goal');

        $this->repository->store([
            'user_id' => $user->id,
            'name' => $name,
            'path' => $path
        ]);
    }
}
