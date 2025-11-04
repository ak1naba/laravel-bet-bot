<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePaginateRequest;
use App\Http\Requests\Event\EventStoreRequest;
use App\Http\Requests\Event\EventUpdateRequest;
use App\Models\Event;
use App\Services\CRUD\EventCRUDService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function __construct(
        private EventCRUDService $eventCRUDService
    ){
    }

    public function index(BasePaginateRequest $request)
    {
        try {
            return new JsonResponse(
                $this->eventCRUDService->indexPaginate(
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Event $Event)
    {
        try {
            return new JsonResponse(
                $this->eventCRUDService->getInstance(
                    $Event
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(EventStoreRequest $request)
    {
        try {
            return new JsonResponse(
                $this->eventCRUDService->create(
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(EventUpdateRequest $request, Event $Event)
    {
        try {
            return new JsonResponse(
                $this->eventCRUDService->update(
                    $Event,
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Event $Event)
    {
        try {
            return new JsonResponse(
                $this->eventCRUDService->delete(
                    $Event
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forceDelete(Event $Event)
    {
        try {
            return new JsonResponse(
                $this->eventCRUDService->forceDelete(
                    $Event
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore(Event $Event)
    {
        try {
            return new JsonResponse(
                $this->eventCRUDService->restore(
                    $Event
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
