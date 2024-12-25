<?php

namespace App\Interfaces;

interface LeagueInterface
{
    /**
     * Get all leagues
     *
     * @return array
     */
    public function getAllLeagues(): array;

    /**
     * Get league by id
     *
     * @param int $id
     * @return array
     */
    public function getLeagueById(int $id): array;

    /**
     * Get leagues by country
     *
     * @param string $country
     * @return array
     */
    public function getLeaguesByCountry(string $country): array;

    /**
     * Get current season leagues
     *
     * @return array
     */
    public function getCurrentSeasonLeagues(): array;
}
