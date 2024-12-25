<?php

namespace App\Http\Controllers;

use App\Repositories\CountryRepository;
use App\Services\Football\FootballApiFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FootballController extends Controller
{
    protected $footballService;
    protected $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->footballService = FootballApiFactory::create();
        $this->countryRepository = $countryRepository;
    }

    public function index()
    {
        $countries = $this->countryRepository->all();
        return view('welcome', compact('countries'));
    }

    public function syncCountries()
    {
        try {
            $countries = $this->footballService->getCountries();
            $this->countryRepository->syncCountries($countries);
            
            return response()->json([
                'message' => 'Ülkeler başarıyla güncellendi',
                'countries' => $this->countryRepository->all()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ülkeler güncellenemedi: ' . $e->getMessage()], 500);
        }
    }

    public function getCountries()
    {
        try {
            $countries = Cache::remember('countries', 3600, function () {
                return $this->countryRepository->all();
            });
            
            return response()->json($countries);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ülkeler alınamadı'], 500);
        }
    }

    public function getAllLeagues()
    {
        try {
            $leagues = Cache::remember('all_leagues', 3600, function () {
                return $this->footballService->getAllLeagues();
            });
            
            return response()->json($leagues);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ligler alınamadı'], 500);
        }
    }

    public function getLeagueById($id)
    {
        try {
            $cacheKey = "league_{$id}";
            $league = Cache::remember($cacheKey, 3600, function () use ($id) {
                return $this->footballService->getLeagueById($id);
            });
            
            return response()->json($league);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lig bilgisi alınamadı'], 500);
        }
    }

    public function getCurrentSeasonLeagues()
    {
        try {
            $leagues = Cache::remember('current_season_leagues', 3600, function () {
                return $this->footballService->getCurrentSeasonLeagues();
            });
            
            return response()->json($leagues);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Güncel sezon ligleri alınamadı'], 500);
        }
    }

    public function getLeaguesByCountry($countryCode)
    {
        try {
            $cacheKey = "leagues_{$countryCode}";
            $leagues = Cache::remember($cacheKey, 3600, function () use ($countryCode) {
                return $this->footballService->getLeaguesByCountry($countryCode);
            });
            
            return response()->json($leagues);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ligler alınamadı'], 500);
        }
    }

    public function getFixtures($leagueId)
    {
        try {
            $fixtures = $this->footballService->getFixturesByLeague($leagueId, [
                'season' => date('Y'),
                'status' => 'NS' // Henüz oynanmamış maçlar
            ]);
            
            return response()->json($fixtures);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Maçlar alınamadı'], 500);
        }
    }
}
