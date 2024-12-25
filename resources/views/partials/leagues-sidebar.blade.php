<div class="space-y-4">
    <!-- Takımlarım -->
    <div>
        <div class="flex items-center space-x-2 mb-2">
            <i class="fas fa-star text-yellow-500"></i>
            <h2 class="text-lg font-semibold">TAKIMLARIM</h2>
        </div>
        <div id="favoriteTeams" class="space-y-2">
            <!-- Favori takımlar buraya dinamik olarak eklenecek -->
        </div>
        <button onclick="showAddTeamModal()" class="text-gray-400 hover:text-white mt-2">
            <i class="fas fa-plus"></i> TAKIMI EKLE
        </button>
    </div>

    <!-- Ligler -->
    <div class="space-y-4">
        <!-- Bundesliga -->
        <div>
            <a href="{{ route('league.matches', ['league' => 'bundesliga']) }}" class="flex items-center space-x-2 hover:bg-gray-700 p-2 rounded-md">
                <img src="https://media.api-sports.io/flags/de.svg" alt="Almanya" class="w-6 h-4">
                <span>Bundesliga</span>
                <button onclick="toggleFavorite('bundesliga')" class="ml-auto">
                    <i class="far fa-star text-gray-400 hover:text-yellow-500"></i>
                </button>
            </a>
        </div>

        <!-- Ligue 1 -->
        <div>
            <a href="{{ route('league.matches', ['league' => 'ligue1']) }}" class="flex items-center space-x-2 hover:bg-gray-700 p-2 rounded-md">
                <img src="https://media.api-sports.io/flags/fr.svg" alt="Fransa" class="w-6 h-4">
                <span>Lig 1</span>
                <button onclick="toggleFavorite('ligue1')" class="ml-auto">
                    <i class="far fa-star text-gray-400 hover:text-yellow-500"></i>
                </button>
            </a>
        </div>

        <!-- Premier League -->
        <div>
            <a href="{{ route('league.matches', ['league' => 'premier-league']) }}" class="flex items-center space-x-2 hover:bg-gray-700 p-2 rounded-md">
                <img src="https://media.api-sports.io/flags/gb.svg" alt="İngiltere" class="w-6 h-4">
                <span>Premier League</span>
                <button onclick="toggleFavorite('premier-league')" class="ml-auto">
                    <i class="far fa-star text-gray-400 hover:text-yellow-500"></i>
                </button>
            </a>
        </div>

        <!-- LaLiga -->
        <div>
            <a href="{{ route('league.matches', ['league' => 'laliga']) }}" class="flex items-center space-x-2 hover:bg-gray-700 p-2 rounded-md">
                <img src="https://media.api-sports.io/flags/es.svg" alt="İspanya" class="w-6 h-4">
                <span>LaLiga</span>
                <button onclick="toggleFavorite('laliga')" class="ml-auto">
                    <i class="far fa-star text-gray-400 hover:text-yellow-500"></i>
                </button>
            </a>
        </div>

        <!-- Serie A -->
        <div>
            <a href="{{ route('league.matches', ['league' => 'serie-a']) }}" class="flex items-center space-x-2 hover:bg-gray-700 p-2 rounded-md">
                <img src="https://media.api-sports.io/flags/it.svg" alt="İtalya" class="w-6 h-4">
                <span>Serie A</span>
                <button onclick="toggleFavorite('serie-a')" class="ml-auto">
                    <i class="far fa-star text-gray-400 hover:text-yellow-500"></i>
                </button>
            </a>
        </div>

        <!-- Süper Lig -->
        <div>
            <a href="{{ route('league.matches', ['league' => 'super-lig']) }}" class="flex items-center space-x-2 hover:bg-gray-700 p-2 rounded-md">
                <img src="https://media.api-sports.io/flags/tr.svg" alt="Türkiye" class="w-6 h-4">
                <span>Süper Lig</span>
                <button onclick="toggleFavorite('super-lig')" class="ml-auto">
                    <i class="far fa-star text-gray-400 hover:text-yellow-500"></i>
                </button>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleFavorite(leagueId) {
        const favorites = JSON.parse(localStorage.getItem('favoriteLeagues') || '[]');
        const index = favorites.indexOf(leagueId);
        
        if (index === -1) {
            favorites.push(leagueId);
        } else {
            favorites.splice(index, 1);
        }
        
        localStorage.setItem('favoriteLeagues', JSON.stringify(favorites));
        updateFavoriteIcons();
    }

    function updateFavoriteIcons() {
        const favorites = JSON.parse(localStorage.getItem('favoriteLeagues') || '[]');
        document.querySelectorAll('[onclick^="toggleFavorite"]').forEach(button => {
            const leagueId = button.getAttribute('onclick').match(/'([^']+)'/)[1];
            const icon = button.querySelector('i');
            if (favorites.includes(leagueId)) {
                icon.classList.remove('far', 'text-gray-400');
                icon.classList.add('fas', 'text-yellow-500');
            } else {
                icon.classList.remove('fas', 'text-yellow-500');
                icon.classList.add('far', 'text-gray-400');
            }
        });
    }

    // Sayfa yüklendiğinde favori ikonlarını güncelle
    document.addEventListener('DOMContentLoaded', updateFavoriteIcons);
</script>
@endpush
