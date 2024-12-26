@extends('layouts.app')

@section('content')
    <style>
        .odds-card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 16px;
            margin-bottom: 16px;
            transition: transform 0.2s ease-in-out;
        }

        .odds-card:hover {
            transform: scale(1.05);
        }

        .odds-card h2 {
            font-size: 18px;
            font-weight: bold;
            color: #333333;
            margin-bottom: 8px;
        }

        .odds-card p {
            font-size: 14px;
            color: #555555;
            margin-bottom: 6px;
        }

        .odds-card .odds-values {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
        }

        .odds-card .date {
            font-size: 14px;
            color: #888888;
            margin-top: 8px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Maç Oranları</h1>
            <!-- Tarih Seçici -->
            <form method="GET" action="{{ url('/odds') }}">
                <input type="date" name="date" value="{{ $selectedDate }}"
                       class="border border-gray-300 rounded px-4 py-2 text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Tarihi Seç
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($matches as $match)
                <div class="odds-card">
                    <div class="mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $match['league'] }} - {{ \Carbon\Carbon::parse($match['date'])->format('Y-m-d H:i') }}</h2>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-center">
                            <img src="{{ $match['home_logo'] }}" alt="{{ $match['home_team'] }}" class="w-12 h-12 mx-auto mb-2">
                            <span class="block text-gray-700 font-bold">{{ $match['home_team'] }}</span>
                        </div>
                        <div class="text-center">
                            <span class="text-gray-600 font-bold">VS</span>
                        </div>
                        <div class="text-center">
                            <img src="{{ $match['away_logo'] }}" alt="{{ $match['away_team'] }}" class="w-12 h-12 mx-auto mb-2">
                            <span class="block text-gray-700 font-bold">{{ $match['away_team'] }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-blue-600">Ev Sahibi Kazanır: {{ $match['odds']['home'] }}</p>
                        <p class="text-blue-600">Beraberlik: {{ $match['odds']['draw'] }}</p>
                        <p class="text-blue-600">Deplasman Kazanır: {{ $match['odds']['away'] }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500">
                    <p class="text-lg">Seçilen tarihte oran bulunmuyor.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
