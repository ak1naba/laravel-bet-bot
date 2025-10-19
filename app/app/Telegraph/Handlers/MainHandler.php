<?php

namespace App\Telegraph\Handlers;


use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;
use App\Telegraph\Modules\StartModule;
use App\Telegraph\Modules\FormModule;
use App\Telegraph\Modules\ProfileModule;
use Illuminate\Support\Facades\Log;

class MainHandler extends WebhookHandler
{
    public function handle(Request $request, TelegraphBot $bot): void
    {
        // Проверяем, есть ли сообщение вообще
        if (!$this->message) {
            \Log::warning('Telegraph: Нет сообщения в обновлении');
            return;
        }

        try {
            $message = $this->message->text();
            
            \Log::info('Telegraph: Входящее сообщение', [
                'message' => $message,
                'chat_id' => $this->chat?->id,
            ]);

            if ($this->isCommand($message)) {
                $command = $this->message->command();
                
                \Log::info('Telegraph: Распознана команда', ['command' => $command]);

                match($command) {
                    'start' => $this->loadHandler(StartModule::class, $request, $bot),
                    'form' => $this->loadHandler(FormModule::class, $request, $bot),
                    'profile' => $this->loadHandler(ProfileModule::class, $request, $bot),
                    default => $this->reply('Неизвестная команда. Используйте /start'),
                };
                return;
            }

            // Проверяем наличие $this->chat перед доступом
            if (!isset($this->chat) || !$this->chat) {
                \Log::warning('Telegraph: $this->chat не инициализирован');
                return;
            }

            $currentModule = $this->chat->getConversationData('current_module');
            
            \Log::info('Telegraph: Проверка текущего модуля', [
                'current_module' => $currentModule,
            ]);

            if ($currentModule) {
                \Log::info('Telegraph: Загрузка модуля', ['module' => $currentModule]);
                $this->loadHandler($currentModule, $request, $bot);
                return;
            }

            $this->reply('Неизвестная команда. Используйте /start');
            
        } catch (\Exception $e) {
            \Log::error('Telegraph: Ошибка в MainHandler', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            if (isset($this->chat) && $this->chat) {
                $this->reply('❌ Ошибка: ' . $e->getMessage());
            }
        }
    }

    private function loadHandler(string $handlerClass, Request $request, TelegraphBot $bot): void
    {
        try {
            \Log::info('Telegraph: Создание экземпляра обработчика', ['handler' => $handlerClass]);
            
            $handler = new $handlerClass($this->telegraph, $this->update);
            $handler->handle($request, $bot);
            
            \Log::info('Telegraph: Обработчик выполнен успешно', ['handler' => $handlerClass]);
            
        } catch (\Exception $e) {
            \Log::error('Telegraph: Ошибка при загрузке обработчика', [
                'handler' => $handlerClass,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            if (isset($this->chat) && $this->chat) {
                $this->reply('❌ Ошибка: ' . $e->getMessage());
            }
        }
    }
}