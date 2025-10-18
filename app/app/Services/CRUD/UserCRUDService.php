<?php

namespace App\Services\CRUD;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserCRUDService extends BaseCRUDService
{
    public function modelClass(): string
    {
        return User::class;
    }

    public function getAuthUser()
    {
        return Auth::user();
    }
}
