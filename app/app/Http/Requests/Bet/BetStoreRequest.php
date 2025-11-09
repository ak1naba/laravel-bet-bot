<?php

namespace App\Http\Requests\Bet;

use Illuminate\Foundation\Http\FormRequest;

class BetStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'market_id' => ['required', 'integer', 'exists:markets,id'],
            'odds_id' => ['required', 'integer', 'exists:odds,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
