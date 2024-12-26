<?php

namespace App\Http\Controllers;

use App\Services\Football\ApiFootballService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MatchController extends Controller
{
    protected $footballApiService;
    protected $matchService;

    protected $popularLeagues = [
        39, // Premier League
        140, // LaLiga
        78, // Bundesliga
        135, // Serie A
        61, // Ligue 1
        203, // Süper Lig
    ];


    public function __construct(ApiFootballService $footballApiService)
    {
        $this->footballApiService = $footballApiService;
    }

    public function index(Request $request)
    {
        // Tarih bilgisi (seçilen tarih yoksa bugünün tarihi alınır)
        $selectedDate = $request->input('date', now()->toDateString());

        // Canlı maçları çek
        $liveResponse = Http::withHeaders([
            'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
            'x-rapidapi-host' => 'v3.football.api-sports.io',
        ])->get('https://v3.football.api-sports.io/fixtures', [
            'live' => 'all', // Canlı maçlar için parametre
        ]);

        $liveMatches = $liveResponse->successful() ? collect($liveResponse->json()['response']) : collect();

        // Seçilen tarihteki maçları çek
        $dateResponse = Http::withHeaders([
            'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
            'x-rapidapi-host' => 'v3.football.api-sports.io',
        ])->get('https://v3.football.api-sports.io/fixtures', [
            'date' => $selectedDate, // Tarihe göre maçlar
        ]);

        $dateMatches = $dateResponse->successful() ? collect($dateResponse->json()['response']) : collect();

        // Verileri view'e gönder
        return view('matches.index', [
            'liveMatches' => $liveMatches,
            'dateMatches' => $dateMatches,
            'selectedDate' => $selectedDate,
        ]);
    }




    public function show($matchId)
    {
        $matchDetails = $this->footballApiService->getMatchDetails($matchId);

        return view('matches.show', [
            'match' => $matchDetails
        ]);
    }

    public function live(Request $request)
    {
        // Tarih bilgisi (seçilen tarih yoksa bugünün tarihi alınır)
        $selectedDate = $request->input('date', now()->toDateString());

        // Canlı maçları al
        $liveMatches = $this->footballApiService->getLiveMatches();

        // Tarihe göre filtreleme (eğer tarih seçilirse)
        $filteredMatches = collect($liveMatches)->filter(function ($match) use ($selectedDate) {
            return $match['date'] === $selectedDate;
        });

        // Verileri view'e gönder
        return view('matches.live', [
            'matches' => $filteredMatches,
            'selectedDate' => $selectedDate,
        ]);
    }




    public function finished(Request $request)
    {
        // Tarih bilgisi (seçilen tarih yoksa bugünün tarihi alınır)
        $selectedDate = $request->input('date', now()->toDateString());

        // Servis üzerinden tamamlanan maçları çek
        $finishedMatches = $this->footballApiService->getFinishedMatches($selectedDate);

        // Popüler liglere göre sıralama yap
        $sortedMatches = collect($finishedMatches)->sortBy(function ($match) {
            // Eğer lig popülerse 0 döndür, değilse 1 döndür
            return in_array($match['league']['id'], $this->popularLeagues) ? 0 : 1;
        })->groupBy('league.name'); // Lig ismine göre gruplandır

        // Verileri view'e gönder
        return view('matches.finished', [
            'matches' => $sortedMatches,
            'selectedDate' => $selectedDate,
        ]);
    }

    public function odds(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString());

        // Maçları getir
        $fixturesResponse = Http::withHeaders([
            'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
            'x-rapidapi-host' => 'v3.football.api-sports.io',
        ])->get('https://v3.football.api-sports.io/fixtures', [
            'date' => $selectedDate,
        ]);

        // Fixtures yanıtını kontrol et
        if (!$fixturesResponse->successful()) {
            \Log::error('Fixtures API Failed', $fixturesResponse->json());
            return view('matches.odds', [
                'matches' => collect(),
                'selectedDate' => $selectedDate,
            ]);
        }

        $fixtures = $fixturesResponse->json()['response'];
        \Log::info('Fixtures Response', $fixtures);

        $matches = collect($fixtures)->map(function ($fixture) {
            // Her maç için oranları çek
            $oddsResponse = Http::withHeaders([
                'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
                'x-rapidapi-host' => 'v3.football.api-sports.io',
            ])->get('https://v3.football.api-sports.io/odds', [
                'fixture' => $fixture['fixture']['id'],
            ]);

            // Odds yanıtını kontrol et
            if (!$oddsResponse->successful()) {
                \Log::error('Odds API Failed for Fixture ID: ' . $fixture['fixture']['id'], $oddsResponse->json());
                return null;
            }

            $odds = $oddsResponse->json()['response'];
            \Log::info('Odds Response for Fixture ID: ' . $fixture['fixture']['id'], $odds);

            return [
                'league' => $fixture['league']['name'],
                'date' => $fixture['fixture']['date'],
                'home_team' => $fixture['teams']['home']['name'] ?? 'Ev Sahibi',
                'home_logo' => $fixture['teams']['home']['logo'] ?? null,
                'away_team' => $fixture['teams']['away']['name'] ?? 'Deplasman',
                'away_logo' => $fixture['teams']['away']['logo'] ?? null,
                'odds' => [
                    'home' => $odds[0]['bookmakers'][0]['bets'][0]['values'][0]['odd'] ?? '-',
                    'draw' => $odds[0]['bookmakers'][0]['bets'][0]['values'][1]['odd'] ?? '-',
                    'away' => $odds[0]['bookmakers'][0]['bets'][0]['values'][2]['odd'] ?? '-',
                ],
            ];
        })->filter(); // Null olanları kaldırır

        return view('matches.odds', [
            'matches' => $matches,
            'selectedDate' => $selectedDate,
        ]);
    }















    public function scheduled()
    {
        $scheduledMatches = $this->footballApiService->getScheduledMatches();

        return view('matches.scheduled', [
            'matches' => $scheduledMatches
        ]);
    }

public function league($league)
    {
        $leagueMatches = $this->footballApiService->getLeagueMatches($league, date('Y-m-d'));

        return view('matches.league', [
            'matches' => $leagueMatches,
            'league' => $league
        ]);
    }

public function refreshLiveMatches()
    {
        $liveMatches = $this->footballApiService->getLiveMatches();

        return response()->json($liveMatches);
    }

}
