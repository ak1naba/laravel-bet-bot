<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePaginateRequest;
use App\Http\Requests\Market\MarketStoreRequest;
use App\Http\Requests\Market\MarketUpdateRequest;
use App\Models\Market;
use App\Models\Event;
use App\Services\CRUD\MarketCRUDService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MarketController extends Controller
{
    public function __construct(
        private MarketCRUDService $marketCRUDService
    ){
    }

    public function index(Event $event, BasePaginateRequest $request)
    {
        try {
            return new JsonResponse(
                $this->marketCRUDService->indexPaginateForEvent(
                    $event,
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Event $event, Market $market)
    {
        try {
            return new JsonResponse(
                $this->marketCRUDService->getInstance(
                    $market
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(MarketStoreRequest $request, Event $event)
    {
        try {
            return new JsonResponse(
                $this->marketCRUDService->create(
                    array_merge($request->validated(), ['event_id' => $event->id])
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(MarketUpdateRequest $request, Event $event, Market $market)
    {
        try {
            return new JsonResponse(
                $this->marketCRUDService->update(
                    $market,
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Event $event, Market $market)
    {
        try {
            return new JsonResponse(
                $this->marketCRUDService->delete(
                    $market
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forceDelete(Event $event, Market $market)
    {
        try {
            return new JsonResponse(
                $this->marketCRUDService->forceDelete(
                    $market
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore(Event $event, Market $market)
    {
        try {
            return new JsonResponse(
                $this->marketCRUDService->restore(
                    $market
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
