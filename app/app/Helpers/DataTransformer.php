<?php

namespace App\Helpers;

class DataTransformer
{
    public function paginatedResponse($collection, $resourceClass)
    {
        return [
            'current_page' => $collection->currentPage(),
            'data' => $resourceClass::collection($collection)->resolve(),
            'first_page_url' => $collection->url(1),
            'from' => $collection->firstItem(),
            'last_page' => $collection->lastPage(),
            'last_page_url' => $collection->url($collection->lastPage()),
            'links' => $collection->linkCollection()->toArray(),
            'next_page_url' => $collection->nextPageUrl(),
            'path' => $collection->path(),
            'per_page' => $collection->perPage(),
            'prev_page_url' => $collection->previousPageUrl(),
            'to' => $collection->lastItem(),
            'total' => $collection->total(),
        ];
    }
}
