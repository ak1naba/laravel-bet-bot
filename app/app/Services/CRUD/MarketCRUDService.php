<?php

namespace App\Services\CRUD;

use App\Models\Market;
use App\Models\Event;
use App\Services\WalletService;

class MarketCRUDService extends BaseCRUDService
{
    public function modelClass(): string
    {
        return Market::class;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Paginate markets for a given event.
     */
    public function indexPaginateForEvent(Event $event, array $params)
    {
        return $this->newQuery()
            ->where('event_id', $event->id)
            ->paginate($params['count_on_page'] ?? -1);
    }

    /**
     * Settle market: update is_win status and calculate all related bets.
     */
    public function settleMarket(Market $market, bool $isWin): Market
    {
        // Update market is_win status
        $market->is_win = $isWin;
        $market->save();

        // Find all bets for this market
        $bets = $market->bets()->where('status', 'pending')->with('user')->get();

        $walletService = app(WalletService::class);

        foreach ($bets as $bet) {
            if ($isWin) {
                // Market won: calculate payout (amount * odd value)
                $bet->status = 'won';
                $bet->payout = $bet->amount * $bet->duplicate_odds;
                
                // Зачисляем выигрыш на кошелёк пользователя
                if ($bet->user) {
                    $walletService->deposit($bet->user, $bet->payout);
                }
            } else {
                // Market lost: no payout
                $bet->status = 'lost';
                $bet->payout = 0;
            }
            $bet->save();
        }

        return $market->fresh();
    }
}
