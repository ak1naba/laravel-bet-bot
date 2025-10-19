<?php

namespace App\Telegraph\Modules;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Models\TelegraphBot;
use Illuminate\Http\Request;

class FormModule extends WebhookHandler
{
    public function handle(Request $request, TelegraphBot $bot): void
    {
        $step = $this->chat->getConversationData('form_step');

        if ($step === null) {
            $this->startForm();
            return;
        }

        if ($step === 'name') {
            $this->handleName();
            return;
        }

        if ($step === 'email') {
            $this->handleEmail();
            return;
        }

        if ($step === 'phone') {
            $this->handlePhone();
            return;
        }
    }

    private function startForm(): void
    {
        $this->chat->storeConversationData('current_module', 'FormModule');
        $this->chat->storeConversationData('form_step', 'name');
        $this->reply('📝 Начинаем заполнение анкеты!\n\nЭтап 1/3 - Как вас зовут?');
    }

    private function handleName(): void
    {
        $name = $this->message?->text();

        if (!$name || strlen($name) < 2) {
            $this->reply('❌ Имя должно быть не менее 2 символов. Попробуйте снова:');
            return;
        }

        $this->chat->storeConversationData('form_name', $name);
        $this->chat->storeConversationData('form_step', 'email');
        $this->reply('✅ Спасибо, ' . $name . '!\n\nЭтап 2/3 - Ваш email:');
    }

    private function handleEmail(): void
    {
        $email = $this->message?->text();

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->reply('❌ Некорректный email. Попробуйте снова:');
            return;
        }

        $this->chat->storeConversationData('form_email', $email);
        $this->chat->storeConversationData('form_step', 'phone');
        $this->reply('✅ Email принят!\n\nЭтап 3/3 - Ваш телефон (формат: +7XXXXXXXXXX):');
    }

    private function handlePhone(): void
    {
        $phone = $this->message?->text();

        if (!$phone || !preg_match('/^\+?[0-9]{10,}$/', str_replace([' ', '-', '(', ')'], '', $phone))) {
            $this->reply('❌ Некорректный номер. Попробуйте снова:');
            return;
        }

        // Сохраняем данные
        $formData = [
            'name' => $this->chat->getConversationData('form_name'),
            'email' => $this->chat->getConversationData('form_email'),
            'phone' => $phone,
        ];

        // Можно сохранить в БД
        // FormSubmission::create($formData);

        $this->reply('✅ Анкета успешно заполнена!\n\n' .
            "Ваши данные:\n" .
            "Имя: " . $formData['name'] . "\n" .
            "Email: " . $formData['email'] . "\n" .
            "Телефон: " . $phone
        );

        // Очищаем данные диалога
        $this->chat->deleteConversationData();
    }
}
