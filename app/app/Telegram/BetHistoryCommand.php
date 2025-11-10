<?php

namespace App\Telegram;

use App\Models\TelegramUser;
use App\Models\Bet;

class BetHistoryCommand extends CommandHandler
{
    public function handle($text = null)
    {
        $normalized = is_string($text) ? mb_strtolower(trim($text)) : $text;
        $tgId = $this->userData['id'] ?? null;

        // Show bet history
        if ($text === '/history' || $normalized === '📊 история ставок' || $normalized === 'история ставок' || $normalized === 'история') {
            if (!$tgId) {
                $this->sendMessage('Не удалось определить Telegram ID.');
                return;
            }

            $telegramUser = TelegramUser::find($tgId);
            if (!$telegramUser || !$telegramUser->user) {
                $this->sendMessage('Аккаунт не привязан. Отправьте /start, чтобы создать аккаунт.');
                return;
            }

            $bets = Bet::where('user_id', $telegramUser->user_id)
                ->with(['market', 'odd'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            if ($bets->isEmpty()) {
                $this->sendMessage('У вас пока нет ставок.');
                return;
            }

            $message = "📊 <b>История ваших ставок</b> (последние 10):\n\n";
            
            foreach ($bets as $bet) {
                $statusEmoji = match($bet->status) {
                    'pending' => '⏳',
                    'won' => '✅',
                    'lost' => '❌',
                    'canceled' => '🚫',
                    default => '❓'
                };
                
                $statusText = match($bet->status) {
                    'pending' => 'В ожидании',
                    'won' => 'Выигрыш',
                    'lost' => 'Проигрыш',
                    'canceled' => 'Отменена',
                    default => 'Неизвестно'
                };

                $message .= "{$statusEmoji} <b>{$bet->duplicate_market}</b>\n";
                $message .= "   💰 Сумма: {$bet->amount}\n";
                $message .= "   📊 Коэф.: {$bet->duplicate_odds}\n";
                
                if ($bet->status === 'won' && $bet->payout) {
                    $message .= "   💵 Выигрыш: <b>{$bet->payout}</b>\n";
                }
                
                $message .= "   📅 {$bet->created_at->format('d.m.Y H:i')}\n";
                $message .= "   Статус: <b>{$statusText}</b>\n\n";
            }

            $totalBets = Bet::where('user_id', $telegramUser->user_id)->count();
            if ($totalBets > 10) {
                $message .= "Всего ставок: {$totalBets}";
            }

            $this->sendMessage($message);
            return;
        }

        // Fallback
        $this->sendMessage('Для просмотра истории ставок нажмите кнопку «История ставок» в меню или отправьте /history.');
    }
}
