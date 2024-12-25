<?php

namespace App\Services\Football;

use App\Interfaces\FootballApiInterface;
use App\Services\Football\Decorators\CacheDecorator;
use App\Services\Football\Decorators\LogDecorator;
use App\Services\Football\Decorators\RateLimitDecorator;

class FootballApiFactory
{
    /**
     * API servisini oluştur
     */
    public function createApi(string $provider = 'api-football'): FootballApiInterface
    {
        return match ($provider) {
            'api-football' => new ApiFootballService(),
            // Gelecekte başka API servisleri eklenebilir
            default => throw new \InvalidArgumentException('Invalid API provider')
        };
    }

    /**
     * API servisini decore et
     */
    public function decorateApi(FootballApiInterface $api, array $decorators = []): FootballApiInterface
    {
        $decoratedApi = $api;

        foreach ($decorators as $decorator) {
            $decoratedApi = match ($decorator) {
                'cache' => new CacheDecorator($decoratedApi),
                'log' => new LogDecorator($decoratedApi),
                'rate-limit' => new RateLimitDecorator($decoratedApi),
                default => throw new \InvalidArgumentException('Invalid decorator')
            };
        }

        return $decoratedApi;
    }
}
