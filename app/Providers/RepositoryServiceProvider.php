<?php

namespace App\Providers;

use App\Interfaces\AuthenticationRepositoryInterface;
use App\Repositories\AuthenticationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthenticationRepositoryInterface::class, AuthenticationRepository::class);
    }
}
