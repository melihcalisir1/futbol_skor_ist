@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container">
        <h1 class="text-center mb-4">Bugün Oynanacak Maçlar</h1>

        @if(count($matches) > 0)
            @php
                // Liglere göre gruplandırma
                $groupedMatches = collect($matches)->groupBy(function ($match) {
                    return $match['league']['name'] ?? 'Diğer Ligler';
                });
            @endphp

            @foreach($groupedMatches as $league => $leagueMatches)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <strong>{{ $league }}</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th>Saat</th>
                                <th>Ev Sahibi</th>
                                <th>Deplasman</th>
                                <th>Durum</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($leagueMatches as $match)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($match['fixture']['date'])->format('H:i') }}</td>
                                    <td>{{ $match['teams']['home']['name'] }}</td>
                                    <td>{{ $match['teams']['away']['name'] }}</td>
                                    <td>{{ $match['fixture']['status']['short'] }}</td>
                                    <td>
                                        @if($match['fixture']['status']['short'] == 'LIVE')
                                            <button class="btn btn-success btn-sm">Canlı İzle</button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Başlamadı</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center">Bugün için herhangi bir maç bulunamadı.</p>
        @endif
    </div>
@endsection
