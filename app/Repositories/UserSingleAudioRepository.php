<?php

namespace App\Repositories;

use App\Models\UserSingleAudio;

class UserSingleAudioRepository
{
    public function store(array $data)
    {
        return UserSingleAudio::create([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'path' => $data['path'],
        ]);
    }
}
