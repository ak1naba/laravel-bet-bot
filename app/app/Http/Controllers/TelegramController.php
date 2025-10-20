<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        Log::info('=== TELEGRAM WEBHOOK CALLED ===');
        Log::info('Raw request content:', ['content' => $request->getContent()]);
        Log::info('Request headers:', $request->headers->all());
        
        try {
            $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
            
            // Получаем сырые данные
            $input = $request->getContent();
            $data = json_decode($input, true);
            Log::info('Decoded JSON data:', $data);
            
            if (isset($data['message']['text'])) {
                $chatId = $data['message']['chat']['id'];
                $text = $data['message']['text'];
                
                Log::info("Processing: '{$text}' from chat: {$chatId}");
                
                if ($text === '/start') {
                    $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Привет! Я бот на Laravel! Наконец-то работаю! 🎉'
                    ]);
                    Log::info('Welcome message sent');
                }
            } else {
                Log::info('No text message in data');
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('ERROR: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}