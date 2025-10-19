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
        // Логируем всё обновление для отладки
        \Log::info('Telegraph: Полное обновление', [
            'update' => $this->update->toArray(),
        ]);

        // Проверяем, это сообщение или callback_query или что-то ещё
        if ($this->update->message()) {
            $this->handleMessage();
        } elseif ($this->update->callbackQuery()) {
            \Log::info('Telegraph: Получен callback_query');
            // Обрабатываем callback если нужно
        } else {
            \Log::warning('Telegraph: Неизвестный тип обновления');
        }
    }

    protected function handleMessage(): void
    {
        if (!$this->message) {
            \Log::warning('Telegraph: Нет сообщения в обновлении');
            return;
        }

        try {
            $message = $this->message->text();

            \Log::info('Telegraph: Входящее текстовое сообщение', [
                'message' => $message,
                'chat_id' => $this->chat?->id,
                'user_id' => $this->message?->from()?->id(),
            ]);

            // Проверяем, это команда
            if ($this->isCommand($message)) {
                $command = $this->message->command();

                \Log::info('Telegraph: Распознана команда', [
                    'command' => $command,
                ]);

                match($command) {
                    'start' => $this->loadHandler(StartModule::class),
                    'form' => $this->loadHandler(FormModule::class),
                    'profile' => $this->loadHandler(ProfileModule::class),
                    default => $this->reply('❌ Неизвестная команда. Используйте /start'),
                };
                return;
            }

            // Проверяем текущий диалог
            if (!isset($this->chat) || !$this->chat) {
                \Log::warning('Telegraph: $this->chat не инициализирован');
                $this->reply('❌ Ошибка инициализации чата');
                return;
            }

            $currentModule = $this->chat->getConversationData('current_module');

            \Log::info('Telegraph: Проверка текущего модуля', [
                'current_module' => $currentModule,
            ]);

            if ($currentModule) {
                \Log::info('Telegraph: Загрузка модуля', ['module' => $currentModule]);
                $this->loadHandler($currentModule);
                return;
            }

            $this->reply('📌 Используйте /start для начала');

        } catch (\Exception $e) {
            \Log::error('Telegraph: Ошибка в handleMessage', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (isset($this->chat) && $this->chat) {
                $this->reply('❌ Ошибка: ' . substr($e->getMessage(), 0, 100));
            }
        }
    }

    private function loadHandler(string $handlerClass): void
    {
        try {
            \Log::info('Telegraph: Создание экземпляра обработчика', [
                'handler' => $handlerClass,
            ]);

            // Создаём новый экземпляр обработчика
            $handler = app($handlerClass, [
                'telegraph' => $this->telegraph,
                'update' => $this->update,
            ]);

            // Вызываем handle напрямую (без параметров, так как они уже в конструкторе)
            $handler->handle($this->request, $this->bot);

            \Log::info('Telegraph: Обработчик выполнен успешно', [
                'handler' => $handlerClass,
            ]);

        } catch (\Exception $e) {
            \Log::error('Telegraph: Ошибка при загрузке обработчика', [
                'handler' => $handlerClass,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (isset($this->chat) && $this->chat) {
                $this->reply('❌ Ошибка модуля: ' . substr($e->getMessage(), 0, 100));
            }
        }
    }
}
