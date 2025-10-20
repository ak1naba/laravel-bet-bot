<?php

namespace App\Telegraph\Handlers;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\DTO\TelegraphUpdate;
use Illuminate\Http\Request;

class MainHandler extends WebhookHandler
{
    public function handle(Request $request, TelegraphBot $bot): void
    {
        try {
            // ✅ Инициализируем стандартные свойства, как это делает родительский класс
            $this->bot = $bot;
            $this->update = TelegraphUpdate::fromArray($request->all(), $bot);
            $this->chat = $this->update->chat();
            $this->message = $this->update->message();

            if ($this->message) {
                $this->handleMessage();
            } else {
                \Log::warning('Telegraph: Нет текстового сообщения в обновлении', [
                    'update' => $request->all(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Telegraph: Ошибка в handle', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    // ==================== ОБРАБОТКА СООБЩЕНИЙ ====================

    protected function handleMessage(): void
    {
        try {
            $message = $this->message->text();

            \Log::info('Telegraph: Входящее сообщение', [
                'message' => $message,
                'chat_id' => $this->chat?->id,
            ]);

            // Проверяем, это команда
            if ($this->isCommand($message)) {
                $command = $this->message->command();

                \Log::info('Telegraph: Распознана команда', [
                    'command' => $command,
                ]);

                match ($command) {
                    'start' => $this->start(),
                    'form' => $this->form(),
                    'profile' => $this->profile(),
                    default => $this->reply('❌ Неизвестная команда. Используйте /start'),
                };
                return;
            }

            // Проверяем текущий активный модуль
            $currentModule = $this->chat?->getConversationData('current_module');

            \Log::info('Telegraph: Проверка текущего модуля', [
                'current_module' => $currentModule,
            ]);

            if ($currentModule) {
                \Log::info('Telegraph: Обработка модуля', ['module' => $currentModule]);
                $this->handleModuleMessage($currentModule);
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

    // ==================== КОМАНДЫ ====================

    private function start(): void
    {
        \Log::info('Telegraph: Обработка /start');

        $this->reply(
            "👋 Добро пожаловать!\n\n" .
            "/form - Заполнить анкету\n" .
            "/profile - Мой профиль\n" .
            "/help - Помощь"
        );
    }

    private function form(): void
    {
        \Log::info('Telegraph: Обработка /form');

        // Сохраняем текущий модуль и начинаем
        $this->chat->storeConversationData('current_module', 'form');
        $this->chat->storeConversationData('form_step', 'name');

        $this->reply('📝 Начинаем заполнение анкеты!\n\nЭтап 1/3 - Как вас зовут?');
    }

    private function profile(): void
    {
        \Log::info('Telegraph: Обработка /profile');

        $this->reply(
            "👤 Профиль пользователя\n\n" .
            "ID: {$this->chat->id}\n" .
            "Статус: Активный"
        );
    }

    // ==================== МОДУЛИ ====================

    private function handleModuleMessage(string $moduleName): void
    {
        match ($moduleName) {
            'form' => $this->handleFormStep(),
            default => $this->reply('❌ Неизвестный модуль'),
        };
    }

    private function handleFormStep(): void
    {
        $step = $this->chat->getConversationData('form_step');
        $message = $this->message->text();

        \Log::info('Telegraph: FormModule::handleFormStep', [
            'step' => $step,
            'message' => $message,
        ]);

        match ($step) {
            'name' => $this->formHandleName($message),
            'email' => $this->formHandleEmail($message),
            'phone' => $this->formHandlePhone($message),
            default => $this->reply('❌ Ошибка в анкете'),
        };
    }

    private function formHandleName(string $name): void
    {
        if (strlen($name) < 2) {
            $this->reply('❌ Имя должно быть не менее 2 символов. Попробуйте снова:');
            return;
        }

        $this->chat->storeConversationData('form_name', $name);
        $this->chat->storeConversationData('form_step', 'email');
        $this->reply("✅ Спасибо, {$name}!\n\nЭтап 2/3 - Ваш email:");
    }

    private function formHandleEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->reply('❌ Некорректный email. Попробуйте снова:');
            return;
        }

        $this->chat->storeConversationData('form_email', $email);
        $this->chat->storeConversationData('form_step', 'phone');
        $this->reply("✅ Email принят!\n\nЭтап 3/3 - Ваш телефон (формат: +7XXXXXXXXXX):");
    }

    private function formHandlePhone(string $phone): void
    {
        $normalized = str_replace([' ', '-', '(', ')'], '', $phone);
        if (!preg_match('/^\+?[0-9]{10,}$/', $normalized)) {
            $this->reply('❌ Некорректный номер. Попробуйте снова:');
            return;
        }

        $formData = [
            'name' => $this->chat->getConversationData('form_name'),
            'email' => $this->chat->getConversationData('form_email'),
            'phone' => $normalized,
        ];

        \Log::info('Telegraph: Анкета заполнена', $formData);

        $this->reply(
            "✅ Анкета успешно заполнена!\n\n" .
            "Ваши данные:\n" .
            "Имя: {$formData['name']}\n" .
            "Email: {$formData['email']}\n" .
            "Телефон: {$normalized}"
        );

        $this->chat->deleteConversationData();
    }
}
