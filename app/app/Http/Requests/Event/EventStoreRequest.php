<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sport_id' => 'required|integer|exists:sports,id',

            'title' => 'required|string|max:255',
            'description' => 'required|string',

            'start_time' => 'required|date|before_or_equal:end_time',
            'end_time' => 'nullable|date|after_or_equal:start_time',

            'status' => 'required|in:scheduled,live,finished',

            'metadata' => 'nullable|array',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
