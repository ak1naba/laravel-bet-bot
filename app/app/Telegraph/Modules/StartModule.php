<?php

namespace App\Telegraph\Modules;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;

class StartModule extends WebhookHandler
{
    public function handle(Request $request, TelegraphBot $bot): void
    {
        $this->reply('Добро пожаловать! 👋\n\n' .
            '/form - Заполнить анкету\n' .
            '/profile - Мой профиль\n' .
            '/help - Помощь'
        );
    }
}
