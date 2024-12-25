<?php

namespace App\Services\Football\Decorators;

use App\Interfaces\FootballApiInterface;
use App\Interfaces\MatchObserverInterface;
use Illuminate\Support\Facades\Cache;

class CacheDecorator implements FootballApiInterface
{
    private FootballApiInterface $api;

    public function __construct(FootballApiInterface $api)
    {
        $this->api = $api;
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
        $cacheKey = "matches_{$date}";
        return Cache::remember($cacheKey, now()->addMinutes(5), fn() => $this->api->getMatches($date));
    }

    public function getLeagueMatches(string $league, string $date): array
    {
        $cacheKey = "league_{$league}_matches_{$date}";
        return Cache::remember($cacheKey, now()->addMinutes(5), fn() => $this->api->getLeagueMatches($league, $date));
    }

    public function getLiveMatches(): array
    {
        // Canlı maçlar için cache kullanmıyoruz
        return $this->api->getLiveMatches();
    }

    public function getFinishedMatches(string $date): array
    {
        $cacheKey = "finished_matches_{$date}";
        return Cache::remember($cacheKey, now()->addHours(1), fn() => $this->api->getFinishedMatches($date));
    }

    public function getScheduledMatches(): array
    {
        return Cache::remember('scheduled_matches', now()->addHours(1), fn() => $this->api->getScheduledMatches());
    }

    public function getMatchDetails(int $matchId): array
    {
        $cacheKey = "match_details_{$matchId}";
        return Cache::remember($cacheKey, now()->addMinutes(5), fn() => $this->api->getMatchDetails($matchId));
    }

    public function getLeagueInfo(string $league): array
    {
        $cacheKey = "league_info_{$league}";
        return Cache::remember($cacheKey, now()->addDays(1), fn() => $this->api->getLeagueInfo($league));
    }

    public function getTeamInfo(int $teamId): array
    {
        $cacheKey = "team_info_{$teamId}";
        return Cache::remember($cacheKey, now()->addDays(1), fn() => $this->api->getTeamInfo($teamId));
    }

    public function getMatchOdds(int $matchId): array
    {
        $cacheKey = "match_odds_{$matchId}";
        return Cache::remember($cacheKey, now()->addMinutes(5), fn() => $this->api->getMatchOdds($matchId));
    }
}
