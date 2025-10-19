<?php

namespace App\Telegraph\Modules;

use DefStudio\Telegraph\Handlers\WebhookHandler;

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
