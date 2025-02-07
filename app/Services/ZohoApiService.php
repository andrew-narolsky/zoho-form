<?php

namespace App\Services;

use App\Contracts\ExternalApiServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

final class ZohoApiService implements ExternalApiServiceInterface
{
    const ZOHO_CACHE_KEY = 'zoho_access_data';
    public function generateTokenFromApi(): string
    {
        $data = [
            'grant_type' => 'authorization_code',
            'client_id' => config('api.zoho.client_id'),
            'client_secret' => config('api.zoho.client_secret'),
            'code' => config('api.zoho.code')
        ];

        return $this->getTokenFromApi(config('api.zoho.url'), $data);
    }

    public function refreshTokenFromApi(): string
    {
        $data = [
            'grant_type' => 'refresh_token',
            'client_id' => config('api.zoho.client_id'),
            'client_secret' => config('api.zoho.client_secret'),
            'refresh_token' => config('api.zoho.refresh_token')
        ];

        return $this->getTokenFromApi(config('api.zoho.url'), $data);
    }

    public function getTokenFromApi(string $url, array $data): string
    {
        $response = Http::asForm()->post($url, $data);

        if (isset($response['error'])) {
            Log::error('Zoho API error: ' . $response['error']);
            return json_encode(['error' => $response['error']]);
        }

        Cache::put(self::ZOHO_CACHE_KEY, $response['access_token'], now()->addHour());

        return $response['access_token'];
    }

    public function getTokenFromCache(): string
    {
        return Cache::get(self::ZOHO_CACHE_KEY) ?? $this->refreshTokenFromApi();
    }

    public function createAccount(Request $request): string
    {
        $oauth_token = 'Zoho-oauthtoken ' . $this->getTokenFromCache();
        $data = [
            'data' => [[
                'Account_Name' => $request->get('name'),
                'Website' => $request->get('website'),
                'Phone' => $request->get('phone')
            ]]
        ];

        return Http::withHeaders(['Authorization' => $oauth_token])
            ->post(config('api.zoho.api_domain') . '/crm/v7/Accounts', $data);
    }

    public function createDeal(Request $request): string
    {
        $oauth_token = 'Zoho-oauthtoken ' . $this->getTokenFromCache();
        $data = [
            'data' => [[
                'Deal_Name' => $request->get('name'),
                'Account_Name' => [
                    'id' => $request->get('account_id')
                ],
                'Stage' => $request->get('stage'),
                'Close_Date' => now()->addWeeks(2)->format('Y-m-d')
            ]]
        ];

        return Http::withHeaders(['Authorization' => $oauth_token])
            ->post(config('api.zoho.api_domain') . '/crm/v7/Deals', $data);
    }
}
