<?php

namespace App\Services\Football\Decorators;

use App\Interfaces\FootballApiInterface;
use App\Interfaces\MatchObserverInterface;

class RateLimitDecorator implements FootballApiInterface
{
    private FootballApiInterface $api;
    private int $requestsPerMinute;
    private array $requestTimes = [];

    public function __construct(FootballApiInterface $api, int $requestsPerMinute = 30)
    {
        $this->api = $api;
        $this->requestsPerMinute = $requestsPerMinute;
    }

    private function checkRateLimit(): void
    {
        $now = time();
        $this->requestTimes = array_filter(
            $this->requestTimes,
            fn($time) => $time > $now - 60
        );

        if (count($this->requestTimes) >= $this->requestsPerMinute) {
            $oldestRequest = min($this->requestTimes);
            $waitTime = 60 - ($now - $oldestRequest);
            if ($waitTime > 0) {
                sleep($waitTime);
            }
        }

        $this->requestTimes[] = $now;
    }

    public function attachObserver(MatchObserverInterface $observer): void
    {
        $this->api->attachObserver($observer);
    }

    public function detachObserver(MatchObserverInterface $observer): void
    {
        $this->api->detachObserver($observer);
    }

    public function getMatches(string $date): array
    {
        $this->checkRateLimit();
        return $this->api->getMatches($date);
    }

    public function getLeagueMatches(string $league, string $date): array
    {
        $this->checkRateLimit();
        return $this->api->getLeagueMatches($league, $date);
    }

    public function getLiveMatches(): array
    {
        $this->checkRateLimit();
        return $this->api->getLiveMatches();
    }

    public function getFinishedMatches(string $date): array
    {
        $this->checkRateLimit();
        return $this->api->getFinishedMatches($date);
    }

    public function getScheduledMatches(): array
    {
        $this->checkRateLimit();
        return $this->api->getScheduledMatches();
    }

    public function getMatchDetails(int $matchId): array
    {
        $this->checkRateLimit();
        return $this->api->getMatchDetails($matchId);
    }

    public function getLeagueInfo(string $league): array
    {
        $this->checkRateLimit();
        return $this->api->getLeagueInfo($league);
    }

    public function getTeamInfo(int $teamId): array
    {
        $this->checkRateLimit();
        return $this->api->getTeamInfo($teamId);
    }

    public function getMatchOdds(int $matchId): array
    {
        $this->checkRateLimit();
        return $this->api->getMatchOdds($matchId);
    }
}
