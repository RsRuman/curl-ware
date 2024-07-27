<?php

namespace App\Repositories;

use App\Interfaces\SocialiteAuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Str;

class SocialiteAuthRepository implements SocialiteAuthRepositoryInterface
{
    /**
     * Storing social user
     * @param $socialUser
     * @param $provider
     * @return mixed
     */
    public function storeUser($socialUser, $provider): mixed
    {
        // First or create user
        $user = User::firstOrCreate(
            [
                'email' => $socialUser->getEmail()
            ],
            [
                'name'              => $socialUser->getName(),
                'password'          => Str::password(8),
                'email_verified_at' => now(),
            ]
        );

        // Update or create provider
        $user->providers()->updateOrCreate(
            [
                'provider_name' => $provider,
                'provider_id'   => $socialUser->getId(),
            ],
            [
                'avatar' => $socialUser->getAvatar()
            ]
        );

        // Assign role
        $user->assignRole('customer');

        return $user->createToken('CurlWare')->plainTextToken;
    }
}
