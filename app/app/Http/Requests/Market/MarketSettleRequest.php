<?php

namespace App\Http\Requests\Market;

use Illuminate\Foundation\Http\FormRequest;

class MarketSettleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_win' => ['required', 'boolean'],
        ];
    }
}
