<?php

namespace App\Interfaces;

interface FootballApiInterface
{
    /**
     * Observer ekle
     */
    public function attachObserver(MatchObserverInterface $observer): void;

    /**
     * Observer çıkar
     */
    public function detachObserver(MatchObserverInterface $observer): void;

    /**
     * Belirli bir tarihteki tüm maçları getir
     */
    public function getMatches(string $date): array;

    /**
     * Belirli bir ligin maçlarını getir
     */
    public function getLeagueMatches(string $league, string $date): array;

    /**
     * Canlı maçları getir
     */
    public function getLiveMatches(): array;

    /**
     * Bitmiş maçları getir
     */
    public function getFinishedMatches(string $date): array;

    /**
     * Programdaki maçları getir
     */
    public function getScheduledMatches(): array;

    /**
     * Maç detaylarını getir
     */
    public function getMatchDetails(int $matchId): array;

    /**
     * Lig bilgilerini getir
     */
    public function getLeagueInfo(string $league): array;

    /**
     * Takım bilgilerini getir
     */
    public function getTeamInfo(int $teamId): array;

    /**
     * Maç oranlarını getir
     */
    public function getMatchOdds(int $matchId): array;
}
