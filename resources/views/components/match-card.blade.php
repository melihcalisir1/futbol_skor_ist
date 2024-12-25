@props(['match'])

<div class="bg-gray-800 rounded-lg p-4 mb-4 hover:bg-gray-700 transition-colors">
    <div class="flex items-center justify-between mb-2">
        <!-- Lig Bilgisi -->
        <div class="flex items-center space-x-2">
            <img src="{{ $match['league']['flag'] }}" alt="{{ $match['league']['country'] }}" class="w-6 h-4">
            <span class="text-sm text-gray-400">{{ $match['league']['name'] }}</span>
            <button onclick="toggleFavorite('{{ $match['league']['id'] }}')" class="text-gray-400 hover:text-yellow-500">
                <i class="far fa-star"></i>
            </button>
        </div>
        
        <!-- Maç Durumu -->
        <div class="flex items-center space-x-2">
            @if($match['status'] === 'LIVE')
                <span class="text-red-500 text-sm font-semibold">{{ $match['minute'] }}'</span>
            @elseif($match['status'] === 'FINISHED')
                <span class="text-gray-400 text-sm">Bitti</span>
            @else
                <span class="text-gray-400 text-sm">{{ $match['time'] }}</span>
            @endif
        </div>
    </div>

    <!-- Takımlar ve Skor -->
    <div class="flex justify-between items-center">
        <!-- Ev Sahibi -->
        <div class="flex items-center space-x-3 flex-1">
            <img src="{{ $match['home_team']['logo'] }}" alt="{{ $match['home_team']['name'] }}" class="w-8 h-8">
            <span class="font-medium">{{ $match['home_team']['name'] }}</span>
        </div>

        <!-- Skor -->
        <div class="flex items-center space-x-3 px-4">
            <span class="text-xl font-bold">{{ $match['score']['home'] }}</span>
            <span class="text-gray-400">-</span>
            <span class="text-xl font-bold">{{ $match['score']['away'] }}</span>
        </div>

        <!-- Deplasman -->
        <div class="flex items-center space-x-3 flex-1 justify-end">
            <span class="font-medium">{{ $match['away_team']['name'] }}</span>
            <img src="{{ $match['away_team']['logo'] }}" alt="{{ $match['away_team']['name'] }}" class="w-8 h-8">
        </div>
    </div>

    <!-- Maç Detayları (opsiyonel) -->
    @if(isset($match['details']))
    <div class="mt-4 pt-4 border-t border-gray-700">
        <div class="grid grid-cols-3 gap-4 text-sm text-gray-400">
            <!-- İstatistikler -->
            <div>
                <div class="flex justify-between mb-2">
                    <span>Topla Oynama</span>
                    <span>{{ $match['details']['possession']['home'] }}% - {{ $match['details']['possession']['away'] }}%</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Şut</span>
                    <span>{{ $match['details']['shots']['home'] }} - {{ $match['details']['shots']['away'] }}</span>
                </div>
            </div>
            
            <!-- Olaylar -->
            <div class="col-span-2">
                @foreach($match['details']['events'] as $event)
                <div class="flex items-center space-x-2 mb-2">
                    <span class="text-gray-500">{{ $event['minute'] }}'</span>
                    @if($event['type'] === 'GOAL')
                        <i class="fas fa-futbol text-green-500"></i>
                    @elseif($event['type'] === 'YELLOW_CARD')
                        <div class="w-3 h-4 bg-yellow-400"></div>
                    @elseif($event['type'] === 'RED_CARD')
                        <div class="w-3 h-4 bg-red-600"></div>
                    @endif
                    <span>{{ $event['player'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
