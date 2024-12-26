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
