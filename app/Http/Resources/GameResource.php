<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            'gamers' => GamerResource::collection($this->users),
            'total_time_sec' => $this->totalTime,
            'total_time' => gmdate('i:s', $this->totalTime),
            'date' => $this->dateTime->format('d.m.Y H:i'),
            'rounds' => RoundResource::collection($this->rounds),
        ];
    }
}
