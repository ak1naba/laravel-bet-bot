<?php

namespace App\Telegram;

use App\Models\User;
use App\Models\TelegramUser;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class StartCommand extends CommandHandler
{
    public function handle($text = null)
    {
        $userInfo = $this->getUserInfo($this->userData);
        
        // Create or find User
        $telegramUser = TelegramUser::find($userInfo['id']);
        
        if (!$telegramUser) {
            // Create new User
            $user = User::create([
                'name' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
                'email' => 'telegram_' . $userInfo['id'] . '@bot.local',
                'password' => Hash::make(uniqid()),
                'role' => 'user'
            ]);
            
            // Create TelegramUser and link to User
            $telegramUser = TelegramUser::create([
                'id' => $userInfo['id'],
                'user_id' => $user->id,
                'firstname' => $userInfo['first_name'],
                'lastname' => $userInfo['last_name'],
                'username' => $userInfo['username'],
                'languagecode' => $userInfo['language_code'],
                'isbot' => $userInfo['is_bot'] ? 'true' : 'false',
            ]);
            
            Log::info('New user created:', ['user_id' => $user->id, 'telegram_id' => $telegramUser->id]);
        }
        
        $keyboard = Keyboard::make()
            ->row([
                Keyboard::button('🏟 События'),
                Keyboard::button('💰 Мой кошелек'),
                Keyboard::button('📊 История ставок'),
            ]);
            
        $message = "👋 Добро пожаловать, {$userInfo['first_name']}!\n\n";
        $message .= "Я ваш телеграм бот для ставок.\n";
        $message .= "Выберите действие из меню ниже:";
        
        $this->sendMessage($message, $keyboard);
    }
}