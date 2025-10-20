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
            'text' => $text
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
            'first_name' => $userData['first_name'] ?? '',
            'last_name' => $userData['last_name'] ?? '',
            'username' => $userData['username'] ?? '',
            'language_code' => $userData['language_code'] ?? 'ru'
        ];
    }
}