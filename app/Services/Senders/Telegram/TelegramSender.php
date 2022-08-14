<?php

namespace App\Services\Senders\Telegram;

use App\Services\Senders\Sender;
use App\Services\Senders\SenderInterface;

class TelegramSender extends Sender implements SenderInterface
{
    public function send(string $user, string $message): void
    {
        // TODO: Implement send() method.
    }
}
