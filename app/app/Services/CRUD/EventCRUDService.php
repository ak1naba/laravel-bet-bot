<?php

namespace App\Services\CRUD;

use App\Helpers\DataTransformer;
use App\Http\Resources\Team\TeamResource;
use App\Models\Event;
use App\Models\Sport;
use App\Models\Team;

class EventCRUDService extends BaseCRUDService
{
    public function modelClass(): string
    {
        return Event::class;
    }

    public function __construct(
        private DataTransformer $dataTransformer,
    ){
        parent::__construct();
    }

}
