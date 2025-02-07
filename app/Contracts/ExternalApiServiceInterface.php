<?php

namespace App\Contracts;

interface ExternalApiServiceInterface
{
    public function generateTokenFromApi(): string;
    public function refreshTokenFromApi(): string;
    public function getTokenFromApi(string $url, array $data): string;
    public function getTokenFromCache(): string;
}
