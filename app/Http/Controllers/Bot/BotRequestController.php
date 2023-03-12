<?php

namespace App\Http\Controllers\Bot;


use App\Models\GameSettingTemplate;
use App\Models\TableOccupation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BotRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function checkTelegramUser()
    {
        $data = request()?->all();
        $telegram_chat_id = $data['chat_id'];
        $user = User::where('telegram_chat_id', $telegram_chat_id)->first();
        if (empty($user)) {
            return ['data' => false];
        }
        return ['data' => true];
    }

    public function saveGameSettings()
    {
        $data = request()?->all();
        if (empty($data)) {
            return ['data' => false];
        }
        $user = User::where('telegram_chat_id', $data['chat_id'])->first()?->toArray();
        $template = GameSettingTemplate::create([
            'mode' => $data['mode'] === 'pvp' ? GameSettingTemplate::PVP_MODE : GameSettingTemplate::TVT_MODE,
            'side' => $data['side'] === 'r' ? GameSettingTemplate::RED_SIDE : GameSettingTemplate::BLUE_SIDE,
            'side_change' => $data['change'] === 'y' ? 1 : 0,
            'user_id' => $user['id'],
        ]);
        return [
            'data' => true,
            'id' => $template->id
        ];
    }

    public function getGameSettings()
    {
        $data = request()?->all();
        $telegramChatId = $data['chat_id'];
        $user = User::where('telegram_chat_id', $telegramChatId)->first()?->toArray();
        $gameSetting = GameSettingTemplate::where('user_id', $user['id'])->get()?->toArray();

        if (empty($gameSetting)) {
            return ['data' => false];
        }
        return [
            'data' => true,
            'settings' => $gameSetting
        ];
    }

    public function saveTelegramData(): bool
    {
        $data = request()?->all();
        $user = User::where('telegram_token', $data['token'])->first();
        $user->telegram_chat_id = $data['user_id'];
        $user->telegram_nickname = $data['username'] ?? $user->login;
        $user->save();
        return true;
    }

    public function getGamers()
    {
        $data = request()?->all();
        $chatId = $data['chat_id'];
        $users = User::where('telegram_chat_id', '!=', $chatId)->where('telegram_chat_id', '!=', '')->get()->toArray();
        $resultData = [];
        foreach ($users as $user) {
            $resultData[] = [
                'login' => $user['login'],
                'chat_id' => $user['telegram_chat_id'],
            ];
        }
        return $resultData;
    }

    public function getGameSettingsById(): array
    {
        $data = request()?->all();
        if (empty($data)) {
            return ['data' => false];
        }
        $templateRow = GameSettingTemplate::find($data['template_id'])?->toArray();
        if (empty($templateRow)) {
            return ['data' => false];
        }
        return [
            'data' => true,
            'settings' => $templateRow
        ];
    }

    public function getUserIdByTelegramChatId()
    {
        $data = request()?->all();
        if (empty($data)) {
            return ['data' => false];
        }
        $userChatId = $data['chat_id'];
        $user = User::where('telegram_chat_id', $userChatId)->first()?->toArray();
        if ($user['id']) {
            return [
                'data' => true,
                'user_id' => $user['id'],
            ];
        }
        return ['data' => false];
    }

    public function setTableBusy()
    {
        $data = request()?->all();
        if (empty($data)) {
            return ['data' => false];
        }
        $dateTime = new \DateTime();
        $startGame = $dateTime->format('Y-m-d H:i:s');
        $endGame = $dateTime->modify('+60 minutes')->format('Y-m-d H:i:s');
        $tableOccupationID = TableOccupation::create([
            'start_game' => $startGame,
            'end_game' => $endGame,
            'user_id' => $data['user_id']
        ]);
        return [
            'data' => true,
            'table_occupation_id' => $tableOccupationID->id,
        ];
    }

    public function isTableOccupied(): array
    {
        $currentDateTime = date('Y-m-d H:i:s');
        $rows = TableOccupation::where('end_game', '>=', $currentDateTime)->first()?->toArray();
        if (empty($rows)) {
            return ['data' => true];
        }
        return ['data' => false];
    }
}
