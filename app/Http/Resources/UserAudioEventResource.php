<?php

namespace App\Http\Resources;

use App\Models\EventParam;
use App\Models\UserAudioEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin UserAudioEvent
 */
class UserAudioEventResource extends JsonResource
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
            'event' => EventResource::make($this->event),
            'path' => '/storage/' . $this->path,
            'parameters' => $this->parameters(),
            'name' => $this->audio_name
        ];
    }

    protected function parameters()
    {
        $eventParameters = EventParam::all();

        $out = [];
        foreach ($this->parameters as $code => $value) {
            $event = $eventParameters->filter(function ($item) use ($code) {
                return $item->code === $code;
            })->first();

            $out[] = [
                'code' => $code,
                'name' => $event->description,
                'value' => $value,
            ];
        }

        return $out;
    }
}
