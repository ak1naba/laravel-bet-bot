<?php

namespace App\Services\CRUD;

use App\Helpers\DataTransformer;
use App\Http\Resources\Team\TeamResource;
use App\Models\Sport;
use App\Models\Team;

class TeamCRUDService extends BaseCRUDService
{
    public function modelClass(): string
    {
        return Team::class;
    }

    public function __construct(
        private DataTransformer $dataTransformer,
    ){
        parent::__construct();
    }

    public function indexPaginate(array $params)
    {
        $pagination = parent::indexPaginate($params);

        return $this->dataTransformer->paginatedResponse($pagination, TeamResource::class);
    }

    public function filter(Sport $sport, array $params){
        $pagination =  $this->newQuery()
            ->where('sport_id', $sport->id)
            ->paginate($params['count_on_page'] ?? -1);

        return $this->dataTransformer->paginatedResponse($pagination, TeamResource::class);
    }
}
