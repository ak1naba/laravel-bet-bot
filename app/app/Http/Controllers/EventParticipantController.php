<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePaginateRequest;
use App\Models\EventParticipant;
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

    public function index(BasePaginateRequest $request)
    {
        try {
            return new JsonResponse(
                $this->service->indexPaginate(
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(EventParticipant $participant)
    {
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

    public function store(Request $request)
    {
        try {
            return new JsonResponse(
                $this->service->create(
                    $request->all()
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, EventParticipant $participant)
    {
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

    public function delete(EventParticipant $participant)
    {
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

    public function forceDelete(EventParticipant $participant)
    {
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

    public function restore(EventParticipant $participant)
    {
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
