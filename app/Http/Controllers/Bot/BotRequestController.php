<?php

namespace App\Http\Controllers\Bot;


use App\Models\GameSettingTemplate;
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
        GameSettingTemplate::create([
            'mode' => $data['mode'] === 'pvp' ? GameSettingTemplate::PVP_MODE : GameSettingTemplate::TVT_MODE,
            'side' => $data['side'] === 'r' ? GameSettingTemplate::RED_SIDE : GameSettingTemplate::BLUE_SIDE,
            'side_change' => $data['change'] === 'y' ? 1 : 0,
            'user_id' => $user['id'],
        ]);
        return ['data' => true];
    }

    public function getGameSettings()
    {
        $data = request()?->all();
        custom_log($data, false);
        $telegramChatId = $data['chat_id'];
        $user = User::where('telegram_chat_id', $telegramChatId)->first()?->toArray();
        $gameSetting = GameSettingTemplate::where('user_id', $user['id'])->first();
        if (! $gameSetting) {
            return ['data' => false];
        }
        return [
            'data' => true,
            'settings' => $gameSetting->toArray()
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
        $users = User::where('telegram_chat_id', '!=', $chatId)->get()->toArray();
        $resultData = [];
        foreach ($users as $user) {
            $resultData[] = [
                'login' => $user['login'],
                'chat_id' => $user['telegram_chat_id'],
            ];
        }
        return $resultData;
    }

    public function getGameSettingsById()
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
        $user = User::where('telegram_chat_id', $userChatId)->first()->toArray();
        if ($user['id']) {
            return [
                'data' => true,
                'user_id' => $user['id'],
            ];
        }
        return ['data' => false];
    }
}
