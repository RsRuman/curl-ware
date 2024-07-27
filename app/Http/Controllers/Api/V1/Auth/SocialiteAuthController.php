<?php

namespace App\Http\Controllers\Api\V1\Auth;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Interfaces\SocialiteAuthRepositoryInterface;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;

#[AllowDynamicProperties]
class SocialiteAuthController extends Controller
{
    public function __construct(SocialiteAuthRepositoryInterface $socialiteAuthRepository)
    {
        $this->socialiteAuthRepository = $socialiteAuthRepository;
    }

    /**
     * Redirect provider
     * @param String $provider
     * @return mixed
     */
    public function redirectToProvider(String $provider): mixed
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return response()->json(['error' => 'Please login using facebook or google'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Handling callback
     * @param String $provider
     * @return JsonResponse
     */
    public function handleProviderCallback(String $provider): JsonResponse
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return Response::json(['error' => 'Please login using facebook or google'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return Response::json(['error' => 'Invalid credentials provided.'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $token = $this->socialiteAuthRepository->storeUser($socialUser, $provider);

        return Response::json([
            'message' => 'Sign in successful.',
            'token'   => $token
        ], HttpResponse::HTTP_OK);
    }
}
