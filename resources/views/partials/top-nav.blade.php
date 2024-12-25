<nav class="bg-gray-900">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center h-14">
            <a href="{{ route('matches.index') }}" 
               class="px-4 py-2 rounded-md text-sm font-medium text-white {{ request()->routeIs('matches.index') ? 'bg-red-600' : 'hover:bg-gray-700' }}">
                TÜMÜ
            </a>
            
            <a href="{{ route('matches.live') }}" 
               class="px-4 py-2 rounded-md text-sm font-medium text-white {{ request()->routeIs('matches.live') ? 'bg-red-600' : 'hover:bg-gray-700' }}">
                CANLI
            </a>
            
            <a href="{{ route('matches.finished') }}" 
               class="px-4 py-2 rounded-md text-sm font-medium text-white {{ request()->routeIs('matches.finished') ? 'bg-red-600' : 'hover:bg-gray-700' }}">
                BİTMİŞ
            </a>
            
            <a href="{{ route('matches.scheduled') }}" 
               class="px-4 py-2 rounded-md text-sm font-medium text-white {{ request()->routeIs('matches.scheduled') ? 'bg-red-600' : 'hover:bg-gray-700' }}">
                PROGRAMLAR
            </a>

            <div class="ml-auto flex items-center">
                <div class="text-white text-sm">
                    {{ \Carbon\Carbon::now()->format('d/m D') }}
                </div>
            </div>
        </div>
    </div>
</nav>
