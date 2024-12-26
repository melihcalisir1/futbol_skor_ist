<?php

namespace App\Http\Controllers;

use App\Services\Football\ApiFootballService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MatchController extends Controller
{
    protected $footballApiService;

    public function __construct(ApiFootballService $footballApiService)
    {
        $this->footballApiService = $footballApiService;
    }

    public function index()
    {
        // Bugünün tarihini al
        $today = now()->toDateString();

        // API çağrısı
        $response = Http::withHeaders([
            'x-rapidapi-key' => env('FOOTBALL_API_KEY'), // FOOTBALL_API_KEY çağırıldı
            'x-rapidapi-host' => 'v3.football.api-sports.io',
        ])->get('https://v3.football.api-sports.io/fixtures', [
            'date' => $today,
        ]);

        // Yanıtı kontrol et
        if ($response->successful()) {
            $matches = $response->json()['response']; // Maçları al
        } else {
            $matches = []; // Hata durumunda boş liste
        }

        // Verileri view'e gönder
        return view('matches.index', compact('matches'));
    }

public function show($matchId)
    {
        $matchDetails = $this->footballApiService->getMatchDetails($matchId);

        return view('matches.show', [
            'match' => $matchDetails
        ]);
    }

    public function live()
    {
        // API'den canlı maçları çek
        $response = Http::withHeaders([
            'x-rapidapi-key' => env('FOOTBALL_API_KEY'),
            'x-rapidapi-host' => 'v3.football.api-sports.io',
        ])->get('https://v3.football.api-sports.io/fixtures', [
            'live' => 'all', // Canlı maçlar için doğru parametre
        ]);

        // Yanıtı kontrol et
        if ($response->successful()) {
            $matches = $response->json()['response']; // Canlı maçlar
        } else {
            $matches = []; // Hata durumunda boş liste
        }

        // Verileri view'e gönder
        return view('matches.live', compact('matches'));
    }


    public function finished()
    {
        $finishedMatches = $this->footballApiService->getFinishedMatches(date('Y-m-d'));

        return view('matches.finished', [
            'matches' => $finishedMatches
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
