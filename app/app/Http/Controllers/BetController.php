<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasePaginateRequest;
use App\Http\Requests\Bet\BetStoreRequest;
use App\Http\Requests\Bet\BetUpdateRequest;
use App\Models\Bet;
use App\Services\CRUD\BetCRUDService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BetController extends Controller
{
    public function __construct(
        private BetCRUDService $betCRUDService
    ){
    }

    public function index(BasePaginateRequest $request)
    {
        try {
            return new JsonResponse(
                $this->betCRUDService->indexPaginate(
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Bet $bet)
    {
        try {
            return new JsonResponse(
                $this->betCRUDService->getInstance(
                    $bet
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(BetStoreRequest $request)
    {
        try {
            $data = $request->validated();

            if (!isset($data['user_id']) && Auth::check()) {
                $data['user_id'] = Auth::id();
            }

            return new JsonResponse(
                $this->betCRUDService->create(
                    $data
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(BetUpdateRequest $request, Bet $bet)
    {
        try {
            return new JsonResponse(
                $this->betCRUDService->update(
                    $bet,
                    $request->validated()
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Bet $bet)
    {
        try {
            return new JsonResponse(
                $this->betCRUDService->delete(
                    $bet
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function forceDelete(Bet $bet)
    {
        try {
            return new JsonResponse(
                $this->betCRUDService->forceDelete(
                    $bet
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore(Bet $bet)
    {
        try {
            return new JsonResponse(
                $this->betCRUDService->restore(
                    $bet
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
