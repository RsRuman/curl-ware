<?php

namespace App\Interfaces;

interface SocialiteAuthRepositoryInterface
{
    public function storeUser($socialUser, $provider);
}
