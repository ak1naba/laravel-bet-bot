<?php

namespace App\Http\Requests\Odd;

use Illuminate\Foundation\Http\FormRequest;

class OddStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric'],
            'market_id' => ['nullable', 'integer', 'exists:markets,id'],
        ];
    }
}
