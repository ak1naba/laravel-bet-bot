<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePaginateRequest;
use App\Http\Requests\Odd\OddStoreRequest;
use App\Http\Requests\Odd\OddUpdateRequest;
use App\Models\Market;
use App\Models\Odd;
use App\Services\CRUD\OddCRUDService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OddController extends Controller
{
    public function __construct(
        private OddCRUDService $oddCRUDService
    ){
    }

    public function index(Market $market, BasePaginateRequest $request)
    {
        try {
            return new JsonResponse(
                $this->oddCRUDService->indexPaginateForMarket(
                    $market,
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Market $market, Odd $odd)
    {
        try {
            return new JsonResponse(
                $this->oddCRUDService->getInstance(
                    $odd
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(OddStoreRequest $request, Market $market)
    {
        try {
            return new JsonResponse(
                $this->oddCRUDService->create(
                    array_merge($request->validated(), ['market_id' => $market->id])
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(OddUpdateRequest $request, Market $market, Odd $odd)
    {
        try {
            return new JsonResponse(
                $this->oddCRUDService->update(
                    $odd,
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Market $market, Odd $odd)
    {
        try {
            return new JsonResponse(
                $this->oddCRUDService->delete(
                    $odd
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forceDelete(Market $market, Odd $odd)
    {
        try {
            return new JsonResponse(
                $this->oddCRUDService->forceDelete(
                    $odd
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore(Market $market, Odd $odd)
    {
        try {
            return new JsonResponse(
                $this->oddCRUDService->restore(
                    $odd
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
