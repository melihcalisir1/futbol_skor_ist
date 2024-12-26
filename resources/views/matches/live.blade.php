@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Canlı Maçlar</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($matches as $match)
                <div class="bg-white shadow-md rounded-lg p-4">
                    <div class="flex justify-between items-center mb-4">
                        <!-- Ev Sahibi Takım -->
                        <div class="flex items-center">
                            <img src="{{ $match['home_team']['logo'] }}"
                                 alt="{{ $match['home_team']['name'] }}"
                                 class="w-12 h-12 mr-2">
                            <span class="font-semibold text-gray-700">{{ $match['home_team']['name'] }}</span>
                        </div>
                        <!-- Skor -->
                        <span class="text-xl font-bold text-blue-600">
                        {{ $match['score']['home'] ?? '-' }} - {{ $match['score']['away'] ?? '-' }}
                    </span>
                        <!-- Deplasman Takım -->
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700">{{ $match['away_team']['name'] }}</span>
                            <img src="{{ $match['away_team']['logo'] }}"
                                 alt="{{ $match['away_team']['name'] }}"
                                 class="w-12 h-12 ml-2">
                        </div>
                    </div>
                    <!-- Lig ve Dakika -->
                    <div class="text-center">
                    <span class="text-sm text-gray-600">
                        {{ $match['league']['name'] }} - {{ $match['minute'] ?? '---' }}.'
                    </span>
                    </div>
                    <!-- Maç Detayları -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('matches.show', $match['id']) }}"
                           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            Maç Detayları
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-600">
                    Şu anda canlı maç bulunmuyor.
                </div>
            @endforelse
        </div>
    </div>
@endsection
