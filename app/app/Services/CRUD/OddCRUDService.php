<?php

namespace App\Services\CRUD;

use App\Models\Odd;
use App\Models\Market;

class OddCRUDService extends BaseCRUDService
{
    public function modelClass(): string
    {
        return Odd::class;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Paginate odds for a given market.
     */
    public function indexPaginateForMarket(Market $market, array $params)
    {
        return $this->newQuery()
            ->where('market_id', $market->id)
            ->paginate($params['count_on_page'] ?? -1);
    }
}
