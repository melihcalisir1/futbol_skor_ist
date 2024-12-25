<?php

namespace App\Http\Controllers;

use App\Services\Football\ApiFootballService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    protected $footballApiService;

    public function __construct(ApiFootballService $footballApiService)
    {
        $this->footballApiService = $footballApiService;
    }

public function index()
    {
        $liveMatches = collect($this->footballApiService->getLiveMatches());
        $todayMatches = collect($this->footballApiService->getMatches(date('Y-m-d')));
        $finishedMatches = collect($this->footballApiService->getFinishedMatches(date('Y-m-d')));
        $scheduledMatches = collect($this->footballApiService->getScheduledMatches());
        
        return view('matches.index', compact(
            'liveMatches', 
            'todayMatches', 
            'finishedMatches', 
            'scheduledMatches'
        ));
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
        $liveMatches = $this->footballApiService->getLiveMatches();
        
        return view('matches.live', [
            'matches' => $liveMatches
        ]);
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
