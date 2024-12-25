<?php

namespace App\Services\Football\Decorators;

use App\Interfaces\FootballApiInterface;
use App\Interfaces\MatchObserverInterface;
use Illuminate\Support\Facades\Log;

class LogDecorator implements FootballApiInterface
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
        Log::info("Fetching matches for date: {$date}");
        return $this->api->getMatches($date);
    }

    public function getLeagueMatches(string $league, string $date): array
    {
        Log::info("Fetching {$league} matches for date: {$date}");
        return $this->api->getLeagueMatches($league, $date);
    }

    public function getLiveMatches(): array
    {
        Log::info("Fetching live matches");
        return $this->api->getLiveMatches();
    }

    public function getFinishedMatches(string $date): array
    {
        Log::info("Fetching finished matches for date: {$date}");
        return $this->api->getFinishedMatches($date);
    }

    public function getScheduledMatches(): array
    {
        Log::info("Fetching scheduled matches");
        return $this->api->getScheduledMatches();
    }

    public function getMatchDetails(int $matchId): array
    {
        Log::info("Fetching details for match: {$matchId}");
        return $this->api->getMatchDetails($matchId);
    }

    public function getLeagueInfo(string $league): array
    {
        Log::info("Fetching info for league: {$league}");
        return $this->api->getLeagueInfo($league);
    }

    public function getTeamInfo(int $teamId): array
    {
        Log::info("Fetching info for team: {$teamId}");
        return $this->api->getTeamInfo($teamId);
    }

    public function getMatchOdds(int $matchId): array
    {
        Log::info("Fetching odds for match: {$matchId}");
        return $this->api->getMatchOdds($matchId);
    }
}
