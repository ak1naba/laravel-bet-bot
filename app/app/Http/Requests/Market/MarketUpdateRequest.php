<?php

namespace App\Http\Requests\Market;

use Illuminate\Foundation\Http\FormRequest;

class MarketUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'participant_id' => ['nullable', 'integer', 'exists:event_participants,id'],
        ];
    }
}
