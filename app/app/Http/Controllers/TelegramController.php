<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        // Логируем факт вызова метода
        Log::info('=== TELEGRAM WEBHOOK CALLED ===');
        
        try {
            $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
            Log::info('Telegram API instance created');
            
            $update = $telegram->getWebhookUpdate();
            Log::info('Update received:', [$update]);
            
            $message = $update->getMessage();
            Log::info('Message extracted:', [$message]);
            
            if ($message && $message->has('text')) {
                $chatId = $message->getChat()->getId();
                $text = $message->getText();
                
                Log::info("Processing message: '{$text}' from chat: {$chatId}");
                
                if ($text === '/start') {
                    Log::info('Sending welcome message...');
                    
                    $response = $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => 'Привет! Я бот на Laravel! Наконец-то работаю! 🎉'
                    ]);
                    
                    Log::info('Message sent successfully:', [$response]);
                } else {
                    Log::info("Unknown command: {$text}");
                }
            } else {
                Log::info('No text message received');
            }
            
            Log::info('=== WEBHOOK COMPLETED SUCCESSFULLY ===');
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('WEBHOOK ERROR: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}