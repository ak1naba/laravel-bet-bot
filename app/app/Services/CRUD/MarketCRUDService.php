<?php

namespace App\Services\CRUD;

use App\Models\Market;
use App\Models\Event;

class MarketCRUDService extends BaseCRUDService
{
    public function modelClass(): string
    {
        return Market::class;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Paginate markets for a given event.
     */
    public function indexPaginateForEvent(Event $event, array $params)
    {
        return $this->newQuery()
            ->where('event_id', $event->id)
            ->paginate($params['count_on_page'] ?? -1);
    }
}
