<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CRUD\UserCRUDService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private UserCRUDService $userCRUDService
    ){
    }

    public function getAuthenticatedUser()
    {
        try {
            return new JsonResponse([
                'user' => $this->userCRUDService->getAuthUser(),
            ]);
        } catch (Exception $exception) {
            return new JsonResponse('Что-то пошло не так', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
