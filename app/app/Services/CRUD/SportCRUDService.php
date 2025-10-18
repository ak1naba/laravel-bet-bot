<?php

namespace App\Services\CRUD;

use App\Models\Sport;

class SportCRUDService extends BaseCRUDService
{
    public function modelClass(): string
    {
        return Sport::class;
    }
}
