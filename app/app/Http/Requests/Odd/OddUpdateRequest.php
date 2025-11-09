<?php

namespace App\Http\Requests\Odd;

use Illuminate\Foundation\Http\FormRequest;

class OddUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['sometimes', 'numeric'],
            'market_id' => ['nullable', 'integer', 'exists:markets,id'],
        ];
    }
}
