<?php

namespace App\Services\CRUD;

use App\Models\EventParticipant;
use App\Models\Event;

class EventParticipantCRUDService extends BaseCRUDService
{
    public function modelClass(): string
    {
        return EventParticipant::class;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Paginate participants for a given event.
     */
    public function indexPaginateForEvent(Event $event, array $params)
    {
        return $this->newQuery()
            ->where('event_id', $event->id)
            ->paginate($params['count_on_page'] ?? -1);
    }
}
