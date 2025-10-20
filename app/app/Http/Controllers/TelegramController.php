<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Telegram\StartCommand;
use App\Telegram\HelpCommand;
use App\Telegram\ProfileCommand;
use App\Telegram\FormWizard;


class TelegramController extends Controller
{
    private $commandMap = [
        '/start' => StartCommand::class,
        '/help' => HelpCommand::class,
        '/profile' => ProfileCommand::class,
        '/form' => FormWizard::class,
        '👤 мой профиль' => ProfileCommand::class,
        'ℹ️ помощь' => HelpCommand::class,
        '📝 заполнить форму' => FormWizard::class,
    ];
    
    public function webhook(Request $request)
    {
        try {
            $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
            $input = $request->all();
            
            if (isset($input['message']['text'])) {
                $chatId = $input['message']['chat']['id'];
                $text = $input['message']['text'];
                $userData = $input['message']['from'];
                
                $this->handleCommand($telegram, $chatId, $text, $userData);
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Telegram error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
    
    private function handleCommand($telegram, $chatId, $text, $userData)
    {
        $commandClass = $this->commandMap[$text] ?? null;
        
        if ($commandClass) {
            $handler = new $commandClass($telegram, $chatId, $userData);
            $handler->handle($text);
        } else {
            // Проверяем, не находится ли пользователь в процессе заполнения формы
            if ($this->isInFormProcess($chatId)) {
                $handler = new FormWizard($telegram, $chatId, $userData);
                $handler->handle($text);
            } else {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Неизвестная команда. Используйте /help для списка команд.'
                ]);
            }
        }
    }
    
    private function isInFormProcess($chatId)
    {
        return \Illuminate\Support\Facades\Cache::has("form_step_{$chatId}");
    }
}