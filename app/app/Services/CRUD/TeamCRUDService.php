<?php

namespace App\Services\CRUD;

use App\Models\Sport;
use App\Models\Team;

class TeamCRUDService extends BaseCRUDService
{
    public function modelClass(): string
    {
        return Team::class;
    }
}
