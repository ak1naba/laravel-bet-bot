<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    public function show()
    {
        $user = Auth::user();
        $balance = $this->walletService->getBalance($user);
        return new JsonResponse(['balance' => $balance], Response::HTTP_OK);
    }

    public function deposit(Request $request)
    {
        $user = Auth::user();
        $amount = $request->input('amount');
        $wallet = $this->walletService->deposit($user, $amount);
        return new JsonResponse(['balance' => $wallet->balance], Response::HTTP_OK);
    }

    public function withdraw(Request $request)
    {
        $user = Auth::user();
        $amount = $request->input('amount');
        try {
            $wallet = $this->walletService->withdraw($user, $amount);
            return new JsonResponse(['balance' => $wallet->balance], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
