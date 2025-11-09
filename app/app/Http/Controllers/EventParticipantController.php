<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePaginateRequest;
use App\Http\Requests\EventParticipant\EventParticipantStoreRequest;
use App\Http\Requests\EventParticipant\EventParticipantUpdateRequest;
use App\Models\EventParticipant;
use App\Models\Event;
use App\Services\CRUD\EventParticipantCRUDService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventParticipantController extends Controller
{
    public function __construct(
        private EventParticipantCRUDService $service
    ){
    }

    public function index(Event $event, BasePaginateRequest $request)
    {
        try {
            return new JsonResponse(
                $this->service->indexPaginateForEvent(
                    $event,
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Event $event, EventParticipant $participant)
    {
        if ($participant->event_id !== $event->id) {
            abort(404, 'Участник не принадлежит событию');
        }
        try {
            return new JsonResponse(
                $this->service->getInstance(
                    $participant
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Event $event, EventParticipantStoreRequest $request)
    {
        try {
            return new JsonResponse(
                $this->service->createForEvent(
                    $event,
                    $request->all()
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Event $event, EventParticipant $participant, EventParticipantUpdateRequest $request)
    {

        if ($participant->event_id !== $event->id) {
            abort(404, 'Участник не принадлежит событию');
        }
        try {
            return new JsonResponse(
                $this->service->update(
                    $participant,
                    $request->all()
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Event $event, EventParticipant $participant)
    {
        if ($participant->event_id !== $event->id) {
            abort(404, 'Участник не принадлежит событию');
        }
        try {
            return new JsonResponse(
                $this->service->delete(
                    $participant
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forceDelete(Event $event, EventParticipant $participant)
    {
        if ($participant->event_id !== $event->id) {
            abort(404, 'Участник не принадлежит событию');
        }
        try {
            return new JsonResponse(
                $this->service->forceDelete(
                    $participant
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore(Event $event, EventParticipant $participant)
    {
        if ($participant->event_id !== $event->id) {
            abort(404, 'Участник не принадлежит событию');
        }
        try {
            return new JsonResponse(
                $this->service->restore(
                    $participant
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
