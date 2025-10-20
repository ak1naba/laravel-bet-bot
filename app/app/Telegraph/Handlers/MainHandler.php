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
        try {
            // ✅ Получаем обновление через метод, а не свойство
            if ($this->message) {
                $this->handleMessage();
            } else {
                \Log::warning('Telegraph: Нет текстового сообщения в обновлении');
            }
        } catch (\Exception $e) {
            \Log::error('Telegraph: Ошибка в handle', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    protected function handleMessage(): void
    {
        try {
            $message = $this->message->text();

            \Log::info('Telegraph: Входящее сообщение', [
                'message' => $message,
                'chat_id' => $this->chat->id,
            ]);

            // Проверяем, это команда
            if ($this->isCommand($message)) {
                $command = $this->message->command();

                \Log::info('Telegraph: Распознана команда', [
                    'command' => $command,
                ]);

                match($command) {
                    'start' => $this->handleStart(),
                    'form' => $this->handleForm(),
                    'profile' => $this->handleProfile(),
                    default => $this->reply('❌ Неизвестная команда. Используйте /start'),
                };
                return;
            }

            // Проверяем текущий диалог
            $currentModule = $this->chat->getConversationData('current_module');

            \Log::info('Telegraph: Проверка текущего модуля', [
                'current_module' => $currentModule,
            ]);

            if ($currentModule) {
                \Log::info('Telegraph: Загрузка модуля', ['module' => $currentModule]);
                $this->loadModule($currentModule);
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

            $this->reply('❌ Ошибка: ' . substr($e->getMessage(), 0, 100));
        }
    }

    private function handleStart(): void
    {
        \Log::info('Telegraph: Обработка /start');
        
        $this->reply('Добро пожаловать! 👋\n\n' .
            '/form - Заполнить анкету\n' .
            '/profile - Мой профиль\n' .
            '/help - Помощь'
        );
    }

    private function handleForm(): void
    {
        \Log::info('Telegraph: Обработка /form');
        
        // Сохраняем текущий модуль
        $this->chat->storeConversationData('current_module', 'FormModule');
        
        // Загружаем модуль
        $this->loadModule('FormModule');
    }

    private function handleProfile(): void
    {
        \Log::info('Telegraph: Обработка /profile');
        
        $this->reply('👤 Профиль пользователя\n\n' .
            'ID: ' . $this->chat->id . '\n' .
            'Статус: Активный'
        );
    }

    private function loadModule(string $moduleName): void
    {
        try {
            \Log::info('Telegraph: Загрузка модуля', ['module' => $moduleName]);

            $moduleClass = match($moduleName) {
                'FormModule' => FormModule::class,
                'ProfileModule' => ProfileModule::class,
                'StartModule' => StartModule::class,
                default => null,
            };

            if (!$moduleClass) {
                \Log::error('Telegraph: Неизвестный модуль', ['module' => $moduleName]);
                $this->reply('❌ Модуль не найден: ' . $moduleName);
                return;
            }

            // ✅ Создаём экземпляр правильно
            $module = app()->make($moduleClass);
            
            // Вызываем обработку
            $module->handle(request(), $this->bot());

            \Log::info('Telegraph: Модуль выполнен успешно', ['module' => $moduleName]);

        } catch (\Exception $e) {
            \Log::error('Telegraph: Ошибка при выполнении модуля', [
                'module' => $moduleName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->reply('❌ Ошибка модуля: ' . substr($e->getMessage(), 0, 100));
        }
    }
}