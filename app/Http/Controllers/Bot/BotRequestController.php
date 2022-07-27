<?php

namespace App\Http\Controllers\Bot;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BotRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
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
}
