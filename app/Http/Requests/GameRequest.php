<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'game_winner_id' => ['required', 'integer', 'min:1'],
            'rounds' => ['required', 'array', 'between:2,3'],
            'rounds.*.winner_id' => ['required', 'integer', 'min:1'],
            'rounds.*.gamers' => ['required', 'array', 'size:2'],
            'rounds.*.gamers.*.user_id' => ['required', 'integer', 'min:1'],
            'rounds.*.gamers.*.score' => ['required', 'integer', 'min:0'],
            'total_time' => ['required', 'integer'],
            'template_id' => ['required', 'integer'],
            'dateTime' => ['required', 'integer'],
        ];
    }
}
