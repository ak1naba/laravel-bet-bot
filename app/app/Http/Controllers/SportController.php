<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePaginateRequest;
use App\Http\Requests\Sport\SportStoreRequest;
use App\Http\Requests\Sport\SportUpdateRequest;
use App\Models\Sport;
use App\Services\CRUD\SportCRUDService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SportController extends Controller
{
    public function __construct(
        private SportCRUDService $sportCRUDService
    ){
    }

    public function index(BasePaginateRequest $request)
    {
        try {
            return new JsonResponse(
                $this->sportCRUDService->indexPaginate(
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Sport $sport)
    {
        try {
            return new JsonResponse(
                $this->sportCRUDService->getInstance(
                    $sport
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(SportStoreRequest $request)
    {
        try {
            return new JsonResponse(
                $this->sportCRUDService->create(
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(SportUpdateRequest $request, Sport $sport)
    {
        try {
            return new JsonResponse(
                $this->sportCRUDService->update(
                    $sport,
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Sport $sport)
    {
        try {
            return new JsonResponse(
                $this->sportCRUDService->delete(
                    $sport
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forceDelete(Sport $sport)
    {
        try {
            return new JsonResponse(
                $this->sportCRUDService->forceDelete(
                    $sport
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore(Sport $sport)
    {
        try {
            return new JsonResponse(
                $this->sportCRUDService->restore(
                    $sport
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
