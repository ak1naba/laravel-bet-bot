<?php

namespace App\Http\Telegram;

abstract class CommandHandler
{
    protected $telegram;
    protected $chatId;
    protected $userData;
    
    public function __construct($telegram, $chatId, $userData = null)
    {
        $this->telegram = $telegram;
        $this->chatId = $chatId;
        $this->userData = $userData;
    }
    
    abstract public function handle($text = null);
    
    protected function sendMessage($text, $keyboard = null)
    {
        $params = [
            'chat_id' => $this->chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ];
        
        if ($keyboard) {
            $params['reply_markup'] = $keyboard;
        }
        
        return $this->telegram->sendMessage($params);
    }
    
    protected function getUserInfo($userData)
    {
        return [
            'id' => $userData['id'],
            'first_name' => $userData['first_name'] ?? 'Не указано',
            'last_name' => $userData['last_name'] ?? 'Не указано',
            'username' => $userData['username'] ?? 'Не указано',
            'language_code' => $userData['language_code'] ?? 'ru',
            'is_bot' => $userData['is_bot'] ?? false
        ];
    }
    
    protected function formatUserInfo($userInfo)
    {
        $info = "👤 <b>Информация о пользователе:</b>\n\n";
        $info .= "🆔 <b>ID:</b> {$userInfo['id']}\n";
        $info .= "👤 <b>Имя:</b> {$userInfo['first_name']}\n";
        $info .= "👥 <b>Фамилия:</b> {$userInfo['last_name']}\n";
        $info .= "📱 <b>Username:</b> @{$userInfo['username']}\n";
        $info .= "🌐 <b>Язык:</b> {$userInfo['language_code']}\n";
        $info .= "🤖 <b>Бот:</b> " . ($userInfo['is_bot'] ? 'Да' : 'Нет') . "\n";
        $info .= "💬 <b>Chat ID:</b> {$this->chatId}";
        
        return $info;
    }
}