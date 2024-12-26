<?php

namespace App\Http\Controllers;

use App\Services\Football\ApiFootballService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MatchController extends Controller
{
    protected $footballApiService;
    protected $matchService;



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

        // Verileri view'e gönder
        return view('matches.finished', [
            'matches' => $finishedMatches, // Servisten dönen maç verileri
            'selectedDate' => $selectedDate, // Seçilen tarih
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
