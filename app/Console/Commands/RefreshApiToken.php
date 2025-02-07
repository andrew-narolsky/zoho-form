<?php

namespace App\Console\Commands;

use App\Contracts\ExternalApiServiceInterface;
use Illuminate\Console\Command;

class RefreshApiToken extends Command
{
    protected $signature = 'api:refresh-token';

    protected $description = 'Refresh API token';

    public function handle(ExternalApiServiceInterface $externalApiService): void
    {
        $externalApiService->refreshTokenFromApi();
        $this->info('API token is successfully refreshing!');
    }
}
