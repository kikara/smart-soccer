<?php

namespace App\Http\Controllers\Websocket;

use App\Models\Game;
use App\Models\GameRound;
use App\Models\Round;
use App\Models\TableOccupation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebsocketRequestController extends Controller
{
    public function saveGame()
    {
        $data = request()?->all();
        if (empty($data)) {
            return ['data' => false];
        }
        $this->saveGameRounds($data);
        $this->updateTableOccupation($data['table_occupation_id']);
        return ['data' => true];
    }

    private function saveGameRounds(array $data)
    {
        $rounds = $data['rounds'];
        $firstRound = $rounds[0];
        $dateTime = new \DateTime;
        $date = $dateTime->setTimestamp((int) $data['dateTime'])->format('Y-m-d H:i:s');

        $game = Game::create([
            'gamerOne' => $firstRound['blue_gamer_id'],
            'gamerTwo' => $firstRound['red_gamer_id'],
            'winner' => $data['game_winner_id'],
            'totalTime' => $data['total_time'],
            'gameSettingTemplateId' => $data['template_id'],
            'dateTime' => $date,
        ]);
        $gameID = $game->id;
        $number = 1;
        $isSideChange = $data['is_side_change'] === 'true';
        foreach ($data['rounds'] as $round) {
            if ($number == 2 && $isSideChange) {
                $gamerOne = $round['red_gamer_id'];
                $gamerTwo = $round['blue_gamer_id'];
                $countOne = $round['red_count'];
                $countTwo = $round['blue_count'];
            } else {
                $gamerOne = $round['blue_gamer_id'];
                $gamerTwo = $round['red_gamer_id'];
                $countOne = $round['blue_count'];
                $countTwo = $round['red_count'];
            }
            $roundModel = Round::create([
                'number' => $number,
                'gamerOne' => $gamerOne,
                'gamerTwo' => $gamerTwo,
                'countOne' => $countOne,
                'countTwo' => $countTwo,
                'winner' => $round['winner_id'],
                'totalTime' => 0,
            ]);

            GameRound::create([
                'gameId' => $gameID,
                'roundId' => $roundModel->id,
            ]);
            $number++;
        }
    }

    private function updateTableOccupation($tableOccupationID)
    {
        $tableOccupation = TableOccupation::find($tableOccupationID);
        $tableOccupation->update([
            'end_game' => date('Y-m-d H:i:s')
        ]);
    }
}
