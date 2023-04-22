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
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'gamers' => UserResource::collection($this->users),
            'total_time_sec' => $this->totalTime,
            'total_time' => gmdate('i:s', $this->totalTime),
            'date' => $this->dateTime->format('d.m.Y H:i'),
            'rounds' => RoundResource::collection($this->rounds),
            'winner_id' => $this->winner,
            'scores' => $this->countScores(),
        ];
    }

    public function countScores()
    {
        $counts = [];
        foreach ($this->rounds as $round) {
            foreach ($round->scores as $score) {
                $count = $counts[$score->user_id] ?? 0;
                $counts[$score->user_id] = $score->score >= 10 ? $count + 1 : $count;
            }
        }

        $out = [];
        foreach ($counts as $userId => $score) {
            $out[] = [
                'id' => $userId,
                'score' => $score,
            ];
        }
        return $out;
    }
}
