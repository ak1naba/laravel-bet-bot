<?php

namespace App\Telegraph\Handlers;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;

class MainHandler extends WebhookHandler
{
    public function start(): void
    {
        $user = $this->message->from();

        $text = "Привет, {$user->firstName()}! 🎉\n\n"
            . "Выберите вариант:\n";

        $this->chat->message($text)->send();
    }
}
