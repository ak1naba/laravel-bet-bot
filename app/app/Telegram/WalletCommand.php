<?php

namespace App\Telegram;

use App\Models\TelegramUser;
use App\Services\WalletService;
use Illuminate\Support\Facades\Cache;

class WalletCommand extends CommandHandler
{
    public function handle($text = null)
    {
        $normalized = is_string($text) ? mb_strtolower(trim($text)) : $text;
        $tgId = $this->userData['id'] ?? null;

        // Show wallet balance
        if ($text === '/wallet' || $normalized === '💰 мой кошелек' || $normalized === 'кошелек') {
            if (!$tgId) {
                $this->sendMessage('Не удалось определить Telegram ID.');
                return;
            }

            $telegramUser = TelegramUser::find($tgId);
            if (!$telegramUser || !$telegramUser->user) {
                $this->sendMessage('Аккаунт не привязан. Отправьте /start, чтобы создать аккаунт.');
                return;
            }

            $walletService = app(WalletService::class);
            $balance = $walletService->getBalance($telegramUser->user);

            $message = "💰 Ваш баланс: <b>{$balance}</b>\n";
            $message .= "Вы можете пополнить счёт или посмотреть историю ставок.";

            $inlineKeyboard = [
                [
                    ['text' => 'Пополнить', 'callback_data' => 'wallet:deposit'],
                ],
            ];

            $this->telegram->sendMessage([
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode(['inline_keyboard' => $inlineKeyboard])
            ]);

            return;
        }

        // If callback to start deposit flow
        if (is_string($text) && str_starts_with($text, 'wallet:deposit')) {
            if (!$tgId) {
                $this->sendMessage('Не удалось определить Telegram ID.');
                return;
            }

            $telegramUser = TelegramUser::find($tgId);
            if (!$telegramUser || !$telegramUser->user) {
                $this->sendMessage('Аккаунт не привязан. Отправьте /start, чтобы создать аккаунт.');
                return;
            }

            // Set pending action in cache for 5 minutes
            Cache::put("telegram:pending:{$tgId}", 'wallet_deposit', 300);

            $this->sendMessage('Введите сумму, на которую хотите пополнить баланс (например: 100.50)');
            return;
        }

        // If user previously asked to deposit, treat next numeric message as amount
        if ($tgId) {
            $pending = Cache::get("telegram:pending:{$tgId}");
            if ($pending === 'wallet_deposit' && is_string($text)) {
                $raw = trim($text);
                // allow comma as decimal separator
                $raw = str_replace(',', '.', $raw);
                if (!is_numeric($raw)) {
                    $this->sendMessage('Неверная сумма. Введите число, например: 100 или 99.50');
                    return;
                }
                $amount = floatval($raw);
                if ($amount <= 0) {
                    $this->sendMessage('Сумма должна быть положительной. Попробуйте ещё раз.');
                    return;
                }

                $telegramUser = TelegramUser::find($tgId);
                if (!$telegramUser || !$telegramUser->user) {
                    $this->sendMessage('Аккаунт не привязан. Отправьте /start, чтобы создать аккаунт.');
                    return;
                }

                $walletService = app(WalletService::class);
                try {
                    $walletService->deposit($telegramUser->user, $amount);
                    $balance = $walletService->getBalance($telegramUser->user);
                    Cache::forget("telegram:pending:{$tgId}");

                    $this->sendMessage("✅ Баланс успешно пополнен на {$amount}. Текущий баланс: <b>{$balance}</b>");
                } catch (\Exception $e) {
                    $this->sendMessage('Ошибка при пополнении: ' . $e->getMessage());
                }

                return;
            }
        }

        // Fallback
        $this->sendMessage('Команда кошелька: нажмите кнопку «Мой кошелек» в меню или отправьте /wallet.');
    }
}
