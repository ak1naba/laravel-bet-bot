<?php

namespace App\Telegram;

class HelpCommand extends CommandHandler
{
    public function handle($text = null)
    {
        $message = "📋 Доступные команды:\n\n";
        $message .= "/start - Начать работу\n";
        $message .= "/help - Помощь\n";
        $message .= "/profile - Мой профиль\n";
        $message .= "/form - Заполнить форму\n\n";
        $message .= "Также вы можете использовать кнопки меню";
        
        $this->sendMessage($message);
    }
}