@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Tamamlanan Maçlar</h1>
            <!-- Tarih Seçici -->
            <form method="GET" action="{{ url('/finished') }}">
                <input type="date" name="date" value="{{ $selectedDate }}"
                       class="border border-gray-300 rounded px-4 py-2 text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Tarihi Seç
                </button>
            </form>
        </div>

        <!-- Liglere Göre Maçlar -->
        @forelse($matches as $league => $leagueMatches)
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-4">{{ $league }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($leagueMatches as $match)
                        <div class="bg-gray-100 shadow-md rounded-lg p-4">
                            <div class="flex justify-between items-center mb-4">
                                <!-- Ev Sahibi Takım -->
                                <div class="flex items-center">
                                    <img src="{{ $match['home_team']['logo'] }}" alt="{{ $match['home_team']['name'] }}" class="w-12 h-12 mr-2">
                                    <span class="font-semibold text-gray-800">{{ $match['home_team']['name'] }}</span>
                                </div>

                                <!-- Skor -->
                                <div class="text-center">
                                    <span class="text-2xl font-bold text-blue-600">{{ $match['score']['home'] }}</span>
                                    <span class="text-lg text-gray-600">-</span>
                                    <span class="text-2xl font-bold text-blue-600">{{ $match['score']['away'] }}</span>
                                </div>

                                <!-- Deplasman Takım -->
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-800">{{ $match['away_team']['name'] }}</span>
                                    <img src="{{ $match['away_team']['logo'] }}" alt="{{ $match['away_team']['name'] }}" class="w-12 h-12 ml-2">
                                </div>
                            </div>

                            <!-- Lig ve Tarih Bilgisi -->
                            <div class="text-center text-sm text-gray-600 mb-2">
                                {{ $match['league']['name'] }} - {{ $match['date'] }}
                            </div>

                            <!-- Maç Detayları Butonu -->
                            <div class="text-center">
                                <a href="{{ route('matches.show', $match['id']) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                    Maç Detayları
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8 text-gray-500">
                <p class="text-lg">Seçilen tarihte tamamlanan maç bulunmuyor.</p>
            </div>
        @endforelse
    </div>
@endsection
