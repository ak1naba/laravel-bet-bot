<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WalletService
{
    public function getOrCreate(User $user): Wallet
    {
        return Wallet::firstOrCreate(['user_id' => $user->id]);
    }

    public function getBalance(User $user): float
    {
        return $this->getOrCreate($user)->balance;
    }

    public function deposit(User $user, float $amount): Wallet
    {
        $wallet = $this->getOrCreate($user);
        $wallet->balance += $amount;
        $wallet->save();
        return $wallet;
    }

    public function withdraw(User $user, float $amount): Wallet
    {
        $wallet = $this->getOrCreate($user);
        if ($wallet->balance < $amount) {
            throw new \Exception('Недостаточно средств');
        }
        $wallet->balance -= $amount;
        $wallet->save();
        return $wallet;
    }
}
