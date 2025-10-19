<?php

namespace App\Telegraph\Handlers;


use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;
use App\Telegraph\Modules\StartModule;
use App\Telegraph\Modules\FormModule;
use App\Telegraph\Modules\ProfileModule;

class MainHandler extends WebhookHandler
{
    public function handle(Request $request, TelegraphBot $bot): void
    {
        $message = $this->message?->text();

        if ($this->isCommand($message)) {
            $command = $this->message->command();

            match($command) {
                'start' => $this->loadHandler(StartModule::class, $request, $bot),
                'form' => $this->loadHandler(FormModule::class, $request, $bot),
                'profile' => $this->loadHandler(ProfileModule::class, $request, $bot),
                default => $this->reply('Неизвестная команда. Используйте /start'),
            };
            return;
        }

        $currentModule = $this->chat->getConversationData('current_module');

        if ($currentModule) {
            $this->loadHandler($currentModule, $request, $bot);
            return;
        }

        $this->reply('Неизвестная команда. Используйте /start ');
    }

    private function loadHandler(string $handlerClass, Request $request, TelegraphBot $bot): void
    {
        $handler = new $handlerClass($this->telegraph, $this->update);
        $handler->handle($request, $bot);
    }
}
