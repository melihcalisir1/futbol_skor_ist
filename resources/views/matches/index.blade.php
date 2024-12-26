@extends('layouts.app')

@section('content')
    <style>
        .card {
            background-color: #ffffff; /* Kartın arka planı beyaz */
            color: #333333; /* Yazı renklerini koyu gri yapıyoruz */
            width: 100%;
            box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .match_info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 10px;
            border-bottom: 1px solid #ddd;
        }

        .team1, .team2 {
            display: flex;
            align-items: center;
            color: #333333; /* Takım isimleri için koyu gri */
        }

        .team1 img, .team2 img {
            height: 50px;
            width: 50px;
            margin-right: 10px;
        }

        .vs {
            background-color: #e0e0e0; /* VS arka planı açık gri */
            color: #333333; /* VS yazısı koyu gri */
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .seeDetails a {
            text-decoration: none;
            color: #007bff; /* LIVE butonu mavi renkte */
            font-weight: bold;
            font-size: 14px;
        }

        .seeDetails a:hover {
            color: #0056b3; /* LIVE butonu hover efekti */
        }

    </style>

        <div class="container">
            <!-- Tarihe Göre Maçlar -->
            @if($dateMatches->isNotEmpty())
                @foreach($dateMatches->groupBy('league.name') as $league => $leagueMatches)
                    <div class="card">
                        <h3 class="card_title">{{ $league }}</h3>
                        @foreach($leagueMatches as $match)
                            <div class="match_info">
                                <!-- Ev Sahibi -->
                                <div class="team1">
                                    <img src="{{ $match['teams']['home']['logo'] ?? '' }}" alt="Home Logo">
                                    <span>
                                {{ $match['teams']['home']['name'] }}
                                        @if($match['goals']['home'] !== null)
                                            <strong>({{ $match['goals']['home'] }})</strong>
                                        @endif
                            </span>
                                </div>
                                <!-- VS -->
                                <div class="vs">
                                    <p>VS</p>
                                </div>
                                <!-- Deplasman -->
                                <div class="team2">
                            <span>
                                {{ $match['teams']['away']['name'] }}
                                @if($match['goals']['away'] !== null)
                                    <strong>({{ $match['goals']['away'] }})</strong>
                                @endif
                            </span>
                                    <img src="{{ $match['teams']['away']['logo'] ?? '' }}" alt="Away Logo">
                                </div>
                                <!-- Detaylar -->
                                <div class="seeDetails">
                                    @if($match['fixture']['status']['short'] === 'FT')
                                        <a href="#" style="color: green; font-weight: bold;">BİTTİ</a>
                                    @else
                                        <a href="#">LIVE</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <p class="text-center text-muted">Seçilen tarihte herhangi bir maç bulunamadı.</p>
            @endif
        </div>
@endsection
