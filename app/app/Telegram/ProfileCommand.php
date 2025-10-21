<?php

namespace App\Telegram;

class ProfileCommand extends CommandHandler
{
    public function handle($text = null)
    {
        $userInfo = $this->getUserInfo($this->userData);
        
        $message = "👤 Ваш профиль:\n\n";
        $message .= "🆔 ID: {$userInfo['id']}\n";
        $message .= "👤 Имя: {$userInfo['first_name']}\n";
        $message .= "👥 Фамилия: {$userInfo['last_name']}\n";
        $message .= "📱 Username: @{$userInfo['username']}\n";
        $message .= "🌐 Язык: {$userInfo['language_code']}\n";
        $message .= "💬 Chat ID: {$this->chatId}";
        
        $this->sendMessage($message);
    }
}