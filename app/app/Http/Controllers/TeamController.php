<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePaginateRequest;
use App\Http\Requests\Team\TeamStoreRequest;
use App\Http\Requests\Team\TeamUpdateRequest;
use App\Models\Team;
use App\Services\CRUD\TeamCRUDService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    public function __construct(
        private TeamCRUDService $teamCRUDService
    ){
    }

    public function index(BasePaginateRequest $request)
    {
        try {
            return new JsonResponse(
                $this->teamCRUDService->indexPaginate(
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Team $team)
    {
        try {
            return new JsonResponse(
                $this->teamCRUDService->getInstance(
                    $team
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(TeamStoreRequest $request)
    {
        try {
            return new JsonResponse(
                $this->teamCRUDService->create(
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(TeamUpdateRequest $request, Team $team)
    {
        try {
            return new JsonResponse(
                $this->teamCRUDService->update(
                    $team,
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Team $team)
    {
        try {
            return new JsonResponse(
                $this->teamCRUDService->delete(
                    $team
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forceDelete(Team $team)
    {
        try {
            return new JsonResponse(
                $this->teamCRUDService->forceDelete(
                    $team
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore(Team $team)
    {
        try {
            return new JsonResponse(
                $this->teamCRUDService->restore(
                    $team
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
