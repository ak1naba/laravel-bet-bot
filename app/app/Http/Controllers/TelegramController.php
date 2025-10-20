<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;

class TelegramController extends Controller
{
    public function webhook()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        
        $update = $telegram->getWebhookUpdate();
        $message = $update->getMessage();
        
        if ($message && $message->has('text')) {
            $chatId = $message->getChat()->getId();
            $text = $message->getText();
            
            if ($text === '/start') {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Привет! Я бот на Laravel!'
                ]);
            }
        }
        
        
        return response()->json(['status' => 'success']);
    }
}