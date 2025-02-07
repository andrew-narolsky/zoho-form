<?php

namespace App\Http\Controllers;

use App\Contracts\ExternalApiServiceInterface;

class ApiController extends Controller
{
    protected object $externalApiService;
    public function __construct(ExternalApiServiceInterface $externalApiService)
    {
        $this->externalApiService = $externalApiService;
    }
    public function generateToken(): string
    {
        return $this->externalApiService->generateTokenFromApi();
    }

    public function refreshToken(): string
    {
        return $this->externalApiService->refreshTokenFromApi();
    }

    public function getToken(): string
    {
        return $this->externalApiService->getTokenFromCache();
    }
}
