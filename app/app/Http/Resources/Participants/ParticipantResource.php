<?php

namespace App\Http\Resources\Participants;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Event */
class ParticipantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'team_id' => $this->team_id,
            'event' => $this->event,
            'team' => $this->team,
        ];
    }
}
