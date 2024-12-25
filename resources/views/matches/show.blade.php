@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg p-6">
        @if(!empty($match))
            <div class="match-header flex justify-between items-center mb-6">
                <div class="flex items-center">
                    <img src="{{ $match['home_team']['logo'] }}" alt="{{ $match['home_team']['name'] }}" class="w-16 h-16 mr-4">
                    <h2 class="text-2xl font-bold">{{ $match['home_team']['name'] }} vs {{ $match['away_team']['name'] }}</h2>
                    <img src="{{ $match['away_team']['logo'] }}" alt="{{ $match['away_team']['name'] }}" class="w-16 h-16 ml-4">
                </div>
            </div>

            <div class="match-details grid grid-cols-2 gap-4 mb-6">
                <div class="match-stats bg-gray-100 p-4 rounded">
                    <h3 class="text-lg font-semibold mb-4">Maç İstatistikleri</h3>
                    <div class="flex justify-between mb-2">
                        <span>Top Hakimiyeti</span>
                        <span>{{ $match['details']['possession']['home'] }}% - {{ $match['details']['possession']['away'] }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Şutlar</span>
                        <span>{{ $match['details']['shots']['home'] }} - {{ $match['details']['shots']['away'] }}</span>
                    </div>
                </div>

            </div>

            <div class="match-events">
                <h3 class="text-lg font-semibold mb-4">Maç Olayları</h3>
                @if(!empty($match['details']['events']))
                    <div class="bg-gray-100 p-4 rounded">
                        @foreach($match['details']['events'] as $event)
                            <div class="flex items-center mb-2">
                                <span class="mr-2 font-medium">{{ $event['minute'] }}.'</span>
                                <span>{{ $event['type'] }}: {{ $event['player'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Henüz bir olay kaydedilmedi.</p>
                @endif
            </div>
        @else
            <p>Maç detayları bulunamadı.</p>
        @endif
    </div>
</div>
@endsection
