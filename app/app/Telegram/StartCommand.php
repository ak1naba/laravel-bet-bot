<?php

namespace App\Telegram;

use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Log;

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
        
        // Логируем информацию о пользователе (вместо сохранения в БД)
        Log::info('New user started bot:', $userInfo);
    }
}