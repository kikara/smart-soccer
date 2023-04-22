<?php

namespace App\Http\Resources;

use App\Models\UserSingleAudio;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin UserSingleAudio
 */
class SingleAudioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => '/storage/' . $this->path,
        ];
    }
}
