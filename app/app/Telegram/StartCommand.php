<?php
// app/Http/Controllers/Telegram/StartCommand.php

namespace App\Http\Telegram;

use Telegram\Bot\Keyboard\Keyboard;

class StartCommand extends CommandHandler
{
    public function handle($text = null)
    {
        $userInfo = $this->getUserInfo($this->userData);
        
        $keyboard = Keyboard::make()
            ->row([
                Keyboard::button('👤 Мой профиль'),
                Keyboard::button('ℹ️ Помощь')
            ])
            ->row([
                Keyboard::button('📝 Заполнить форму'),
                Keyboard::button('⚙️ Настройки')
            ]);
            
        $message = "👋 Добро пожаловать, {$userInfo['first_name']}!\n\n";
        $message .= "Я ваш телеграм бот на Laravel.\n";
        $message .= "Выберите действие из меню ниже:";
        
        $this->sendMessage($message, $keyboard);
        
        // Сохраняем пользователя в БД
        $this->saveUser($userInfo);
    }
    
    private function saveUser($userInfo)
    {
        // Здесь логика сохранения пользователя в базу
        \App\Models\TelegramUser::firstOrCreate(
            ['telegram_id' => $userInfo['id']],
            [
                'first_name' => $userInfo['first_name'],
                'last_name' => $userInfo['last_name'],
                'username' => $userInfo['username'],
                'language_code' => $userInfo['language_code'],
            ]
        );
    }
}