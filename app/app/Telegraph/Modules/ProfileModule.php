<?php

namespace App\Telegraph\Modules;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;

class ProfileModule extends WebhookHandler
{
    public function handle(Request $request, TelegraphBot $bot): void
    {
        $this->chat->storeConversationData('current_module', null);
        $this->reply('👤 Профиль пользователя\n\n' .
            'ID: ' . $this->chat->id . '\n' .
            'Статус: Активный'
        );
    }
}
