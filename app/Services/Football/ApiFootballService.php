<?php

namespace App\Services\Football;

use App\Interfaces\FootballApiInterface;
use App\Interfaces\MatchObserverInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ApiFootballService implements FootballApiInterface
{
    private array $observers = [];
    private string $apiKey;
    private string $baseUrl = 'https://api-football-v1.p.rapidapi.com/v3';

    public function __construct()
    {
        $this->apiKey = config('services.football-api.key');
    }

    public function attachObserver(MatchObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    public function detachObserver(MatchObserverInterface $observer): void
    {
        $this->observers = array_filter(
            $this->observers,
            fn($o) => $o !== $observer
        );
    }

    public function getMatches(string $date): array
    {
        $cacheKey = "matches_{$date}";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($date) {
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => $this->apiKey,
                'X-RapidAPI-Host' => 'api-football-v1.p.rapidapi.com'
            ])->get("{$this->baseUrl}/fixtures", [
                'date' => $date
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            return isset($data['response']) ? $this->formatMatches($data['response']) : [];
        });
    }

    public function getLeagueMatches(string $league, string $date): array
    {
        $cacheKey = "league_{$league}_matches_{$date}";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($league, $date) {
            $leagueId = $this->getLeagueId($league);

            $response = Http::withHeaders([
                'X-RapidAPI-Key' => $this->apiKey,
                'X-RapidAPI-Host' => 'api-football-v1.p.rapidapi.com'
            ])->get("{$this->baseUrl}/fixtures", [
                'league' => $leagueId,
                'date' => $date
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            return isset($data['response']) ? $this->formatMatches($data['response']) : [];
        });
    }

    public function getLiveMatches(): array
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
            'X-RapidAPI-Host' => 'api-football-v1.p.rapidapi.com'
        ])->get("{$this->baseUrl}/fixtures", [
            'live' => 'all'
        ]);

        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();
        $matches = isset($data['response']) ? $this->formatMatches($data['response']) : [];

        // Observer'ları bilgilendir
        foreach ($this->observers as $observer) {
            $observer->notify($matches);
        }

        return $matches;
    }

    public function getFinishedMatches(string $date): array
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Key' => $this->apiKey,
            'X-RapidAPI-Host' => 'v3.football.api-sports.io',
        ])->get("https://v3.football.api-sports.io/fixtures", [
            'date' => $date, // Seçilen tarih
            'status' => 'FT', // Maç bitmiş durumu
        ]);

        // Eğer yanıt başarısızsa boş bir dizi döndür
        if (!$response->successful()) {
            return [];
        }

        $data = $response->json();

        // Yanıtı formatla ve döndür
        return isset($data['response']) ? $this->formatMatches($data['response']) : [];
    }




    public function getScheduledMatches(): array
    {
        $cacheKey = 'scheduled_matches';

        return Cache::remember($cacheKey, now()->addHours(1), function () {
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => $this->apiKey,
                'X-RapidAPI-Host' => 'api-football-v1.p.rapidapi.com'
            ])->get("{$this->baseUrl}/fixtures", [
                'next' => 50
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            return isset($data['response']) ? $this->formatMatches($data['response']) : [];
        });
    }

    public function getMatchDetails(int $matchId): array
    {
        $cacheKey = "match_details_{$matchId}";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($matchId) {
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => $this->apiKey,
                'X-RapidAPI-Host' => 'api-football-v1.p.rapidapi.com'
            ])->get("{$this->baseUrl}/fixtures", [
                'id' => $matchId
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            return isset($data['response'][0]) ? $this->formatMatchDetails($data['response'][0]) : [];
        });
    }

    public function getLeagueInfo(string $league): array
    {
        $cacheKey = "league_info_{$league}";

        return Cache::remember($cacheKey, now()->addDays(1), function () use ($league) {
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => $this->apiKey,
                'X-RapidAPI-Host' => 'api-football-v1.p.rapidapi.com'
            ])->get("{$this->baseUrl}/leagues", [
                'name' => $league
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            return isset($data['response'][0]) ? $this->formatLeagueInfo($data['response'][0]) : [];
        });
    }

    public function getTeamInfo(int $teamId): array
    {
        $cacheKey = "team_info_{$teamId}";

        return Cache::remember($cacheKey, now()->addDays(1), function () use ($teamId) {
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => $this->apiKey,
                'X-RapidAPI-Host' => 'api-football-v1.p.rapidapi.com'
            ])->get("{$this->baseUrl}/teams", [
                'id' => $teamId
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            return isset($data['response'][0]) ? $this->formatTeamInfo($data['response'][0]) : [];
        });
    }

    private function formatMatches(array $matches): array
    {
        return array_map(function ($match) {
            return [
                'id' => $match['fixture']['id'],
                'date' => date('Y-m-d', strtotime($match['fixture']['date'])),
                'time' => date('H:i', strtotime($match['fixture']['date'])),
                'status' => $this->mapStatus($match['fixture']['status']['short']),
                'minute' => $match['fixture']['status']['elapsed'],
                'league' => [
                    'id' => $match['league']['id'],
                    'name' => $match['league']['name'],
                    'country' => $match['league']['country'],
                    'flag' => $match['league']['flag']
                ],
                'home_team' => [
                    'id' => $match['teams']['home']['id'],
                    'name' => $match['teams']['home']['name'],
                    'logo' => $match['teams']['home']['logo']
                ],
                'away_team' => [
                    'id' => $match['teams']['away']['id'],
                    'name' => $match['teams']['away']['name'],
                    'logo' => $match['teams']['away']['logo']
                ],
                'score' => [
                    'home' => $match['goals']['home'] ?? 0,
                    'away' => $match['goals']['away'] ?? 0
                ]
            ];
        }, $matches);
    }

    private function formatMatchDetails(array $match): array
    {
        return [
            'details' => [
                'possession' => [
                    'home' => $match['statistics'][0]['statistics'][9]['value'] ?? 0,
                    'away' => $match['statistics'][1]['statistics'][9]['value'] ?? 0
                ],
                'shots' => [
                    'home' => $match['statistics'][0]['statistics'][2]['value'] ?? 0,
                    'away' => $match['statistics'][1]['statistics'][2]['value'] ?? 0
                ],
                'events' => array_map(function ($event) {
                    return [
                        'id' => $event['time']['elapsed'] . $event['team']['id'] . $event['player']['id'],
                        'minute' => $event['time']['elapsed'],
                        'type' => $this->mapEventType($event['type']),
                        'player' => $event['player']['name'],
                        'player_in' => $event['assist']['name'] ?? null,
                        'player_out' => $event['player']['name'] ?? null
                    ];
                }, $match['events'] ?? [])
            ]
        ];
    }

    private function formatLeagueInfo(array $league): array
    {
        return [
            'id' => $league['league']['id'],
            'name' => $league['league']['name'],
            'country' => $league['country']['name'],
            'flag' => $league['country']['flag'],
            'logo' => $league['league']['logo'],
            'season' => $league['seasons'][0]['year']
        ];
    }

    private function formatTeamInfo(array $team): array
    {
        return [
            'id' => $team['team']['id'],
            'name' => $team['team']['name'],
            'country' => $team['team']['country'],
            'founded' => $team['team']['founded'],
            'logo' => $team['team']['logo']
        ];
    }

    private function mapStatus(string $status): string
    {
        return match ($status) {
            '1H', '2H', 'ET' => 'LIVE',
            'HT' => 'HALF_TIME',
            'FT' => 'FINISHED',
            'PST' => 'POSTPONED',
            'CANC' => 'CANCELLED',
            default => 'NOT_STARTED'
        };
    }

    private function mapEventType(string $type): string
    {
        return match ($type) {
            'Goal' => 'GOAL',
            'Card' => 'YELLOW_CARD',
            'subst' => 'SUBSTITUTION',
            default => $type
        };
    }

    public function getMatchOdds(int $matchId): array
    {
        $cacheKey = "match_odds_{$matchId}";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($matchId) {
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => $this->apiKey,
                'X-RapidAPI-Host' => 'api-football-v1.p.rapidapi.com'
            ])->get("{$this->baseUrl}/fixtures/{$matchId}/odds", [
                'bookmaker' => 1
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            if (!isset($data['response'][0]['bookmakers'][0]['bets'])) {
                return [];
            }

            return $this->formatOdds($data['response'][0]['bookmakers'][0]['bets']);
        });
    }

    private function formatOdds(array $bets): array
    {
        foreach ($bets as $bet) {
            if ($bet['id'] === 1) { // Match Winner bet type
                $odds = [
                    'home' => null,
                    'draw' => null,
                    'away' => null
                ];

                foreach ($bet['values'] as $value) {
                    switch ($value['value']) {
                        case 'Home':
                        case '1':
                            $odds['home'] = $value['odd'];
                            break;
                        case 'Draw':
                        case 'X':
                            $odds['draw'] = $value['odd'];
                            break;
                        case 'Away':
                        case '2':
                            $odds['away'] = $value['odd'];
                            break;
                    }
                }

                return [$odds];
            }
        }

        return [];
    }

    private function getLeagueId(string $league): int
    {
        return match ($league) {
            'premier-league' => 39,
            'laliga' => 140,
            'bundesliga' => 78,
            'serie-a' => 135,
            'ligue1' => 61,
            'super-lig' => 203,
            default => throw new \InvalidArgumentException('Invalid league')
        };
    }
}
