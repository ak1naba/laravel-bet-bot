<?php

namespace App\Telegram;

use App\Models\TelegramUser;
use App\Models\Market;
use App\Models\Odd;
use App\Models\Bet;
use App\Services\WalletService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BetCommand extends CommandHandler
{
    public function handle($text = null)
    {
        $tgId = $this->userData['id'] ?? null;

        // Обработка callback для создания ставки: bet:create:{marketId}:{oddId}
        if (is_string($text) && str_starts_with($text, 'bet:create:')) {
            $parts = explode(':', $text);
            $marketId = isset($parts[2]) ? intval($parts[2]) : null;
            $oddId = isset($parts[3]) ? intval($parts[3]) : null;

            if (!$marketId || !$oddId) {
                $this->sendMessage('Неверные параметры ставки.');
                return;
            }

            $market = Market::find($marketId);
            $odd = Odd::find($oddId);

            if (!$market || !$odd) {
                $this->sendMessage('Маркет или коэффициент не найдены.');
                return;
            }

            // Сохраняем в кэш информацию о ставке
            Cache::put("telegram:bet_pending:{$tgId}", [
                'market_id' => $marketId,
                'odd_id' => $oddId,
            ], 300);

            $msg = "💰 Введите сумму ставки на:\n";
            $msg .= "<b>{$market->description}</b>\n";
            $msg .= "Коэффициент: <b>{$odd->value}</b>\n\n";
            $msg .= "Введите сумму (например: 100 или 50.50):";

            $this->sendMessage($msg);
            return;
        }

        // Если пользователь ввёл сумму после запроса на ставку
        if ($tgId && Cache::has("telegram:bet_pending:{$tgId}")) {
            $betData = Cache::get("telegram:bet_pending:{$tgId}");
            
            if (is_string($text)) {
                $raw = trim($text);
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
                $balance = $walletService->getBalance($telegramUser->user);

                if ($balance < $amount) {
                    $this->sendMessage("❌ Недостаточно средств. Ваш баланс: <b>{$balance}</b>");
                    return;
                }

                try {
                    // Списываем сумму с кошелька
                    $walletService->withdraw($telegramUser->user, $amount);

                    // Создаём ставку
                    $bet = Bet::create([
                        'user_id' => $telegramUser->user_id,
                        'market_id' => $betData['market_id'],
                        'odds_id' => $betData['odd_id'],
                        'amount' => $amount,
                        'status' => 'pending',
                    ]);

                    Cache::forget("telegram:bet_pending:{$tgId}");
                    
                    $newBalance = $walletService->getBalance($telegramUser->user);
                    $odd = Odd::find($betData['odd_id']);
                    $potentialWin = $amount * $odd->value;

                    $msg = "✅ Ставка успешно размещена!\n\n";
                    $msg .= "💰 Сумма: <b>{$amount}</b>\n";
                    $msg .= "📊 Коэффициент: <b>{$odd->value}</b>\n";
                    $msg .= "💵 Возможный выигрыш: <b>{$potentialWin}</b>\n";
                    $msg .= "💳 Новый баланс: <b>{$newBalance}</b>";

                    $this->sendMessage($msg);
                } catch (\Exception $e) {
                    Log::error('Bet creation error: ' . $e->getMessage());
                    $this->sendMessage('Ошибка при создании ставки: ' . $e->getMessage());
                }

                return;
            }
        }

        // Fallback
        $this->sendMessage('Для создания ставки выберите маркет в списке событий.');
    }
}
