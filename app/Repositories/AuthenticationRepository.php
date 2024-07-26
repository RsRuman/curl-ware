<?php

namespace App\Repositories;

use App\Interfaces\AuthenticationRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuthenticationRepository implements AuthenticationRepositoryInterface
{
    public function storeUser(array $data)
    {
        return User::create($data);
    }

    public function getToken(array $data)
    {
        if (Auth::attempt($data)) {
            $user = Auth::user();
            return $user->createToken('CurlWare')->plainTextToken;
        }

        return false;
    }

    public function deleteToken(Model|Builder $user)
    {
        return $user->currentAccessToken()->delete();
    }
}
