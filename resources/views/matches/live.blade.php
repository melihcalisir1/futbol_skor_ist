@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Canlı Maçlar</h1>

        @if(count($matches) > 0)
            @php
                // Liglere göre gruplandırma
                $groupedMatches = collect($matches)->groupBy(function ($match) {
                    return $match['league']['name'] ?? 'Diğer Ligler';
                });
            @endphp

            @foreach($groupedMatches as $league => $leagueMatches)
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <strong>{{ $league }}</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th>Saat</th>
                                <th>Ev Sahibi</th>
                                <th>Deplasman</th>
                                <th>Skor</th>
                                <th>Durum</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($leagueMatches as $match)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($match['fixture']['date'])->format('H:i') }}</td>
                                    <td>{{ $match['teams']['home']['name'] }}</td>
                                    <td>{{ $match['teams']['away']['name'] }}</td>
                                    <td>
                                        {{ $match['goals']['home'] ?? 0 }} - {{ $match['goals']['away'] ?? 0 }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $match['fixture']['status']['elapsed'] ?? 0 }}'</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center">Şu anda canlı maç bulunmuyor.</p>
        @endif
    </div>
@endsection
