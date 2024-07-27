<?php

namespace App\Providers;

use App\Interfaces\AuthenticationRepositoryInterface;
use App\Interfaces\SocialiteAuthRepositoryInterface;
use App\Repositories\AuthenticationRepository;
use App\Repositories\SocialiteAuthRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthenticationRepositoryInterface::class, AuthenticationRepository::class);
        $this->app->bind(SocialiteAuthRepositoryInterface::class, SocialiteAuthRepository::class);
    }
}
