<?php

namespace App\Http\Requests\Market;

use Illuminate\Foundation\Http\FormRequest;

class MarketStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'participant_id' => ['nullable', 'integer', 'exists:event_participants,id'],
        ];
    }
}
