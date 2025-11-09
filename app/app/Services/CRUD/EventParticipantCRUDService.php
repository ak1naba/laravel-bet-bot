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

    public function getInstanceForEvent(EventParticipant $participant)
    {
        return $participant;
    }

    public function createForEvent(Event $event, array $data)
    {
        $data['event_id'] = $event->id;
        return $this->model->create($data);
    }

    public function updateForEvent(EventParticipant $participant, array $data)
    {
        $participant->update($data);
        return $participant;
    }

    public function deleteForEvent(EventParticipant $participant)
    {
        return $participant->delete();
    }

    public function forceDeleteForEvent(EventParticipant $participant)
    {
        
        return $participant->forceDelete();
    }

    public function restoreForEvent(EventParticipant $participant)
    {
        $participant->restore();
        return $participant;
    }
}
