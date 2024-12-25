<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futbol Skor İstatistik</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sol Bar -->
        <div class="w-64 bg-white shadow-lg">
            <!-- Logo Alanı -->
            <div class="p-4 border-b">
                <h1 class="text-xl font-bold text-gray-800">Futbol Skor İst</h1>
            </div>
            
            <!-- Popüler Ligler -->
            <div class="p-4">
                <h2 class="text-sm font-semibold text-gray-600 mb-4">POPÜLER LİGLER</h2>
                <ul class="space-y-2">
                    <li class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer">
                        <img src="" alt="PL" class="w-6 h-6 mr-3">
                        <span class="text-sm">Premier League</span>
                    </li>
                    <li class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer">
                        <img src="" alt="LL" class="w-6 h-6 mr-3">
                        <span class="text-sm">La Liga</span>
                    </li>
                    <li class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer">
                        <img src="" alt="BL" class="w-6 h-6 mr-3">
                        <span class="text-sm">Bundesliga</span>
                    </li>
                    <li class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer">
                        <img src="" alt="SA" class="w-6 h-6 mr-3">
                        <span class="text-sm">Serie A</span>
                    </li>
                    <li class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer">
                        <img src="" alt="L1" class="w-6 h-6 mr-3">
                        <span class="text-sm">Ligue 1</span>
                    </li>
                    <li class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer">
                        <img src="" alt="SL" class="w-6 h-6 mr-3">
                        <span class="text-sm">Süper Lig</span>
                    </li>
                    <li class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer">
                        <img src="" alt="ED" class="w-6 h-6 mr-3">
                        <span class="text-sm">Eredivisie</span>
                    </li>
                    <li class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer">
                        <img src="" alt="PL" class="w-6 h-6 mr-3">
                        <span class="text-sm">Primeira Liga</span>
                    </li>
                    <li class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer">
                        <img src="" alt="PRL" class="w-6 h-6 mr-3">
                        <span class="text-sm">Pro League</span>
                    </li>
                    <li class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer">
                        <img src="" alt="SPL" class="w-6 h-6 mr-3">
                        <span class="text-sm">Saudi Pro League</span>
                    </li>
                </ul>
            </div>

            <!-- Tüm Ülkeler -->
            <div class="p-4 border-t">
                <h2 class="text-sm font-semibold text-gray-600 mb-4">TÜM ÜLKELER</h2>
                <div id="countries-list" class="space-y-2">
                    <!-- JavaScript ile doldurulacak -->
                </div>
            </div>
        </div>

        <!-- Ana İçerik -->
        <div class="flex-1 p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Hoş Geldiniz</h2>
            <p class="text-gray-600">Sol menüden bir lig veya ülke seçin.</p>
        </div>
    </div>

    <script>
        // Observer Pattern Implementation
        class EventEmitter {
            constructor() {
                this.events = {};
            }

            on(event, callback) {
                if (!this.events[event]) {
                    this.events[event] = [];
                }
                this.events[event].push(callback);
            }

            emit(event, data) {
                if (this.events[event]) {
                    this.events[event].forEach(callback => callback(data));
                }
            }
        }

        // State Management
        const store = {
            countries: [],
            selectedCountry: null,
            leagues: [],
            selectedLeague: null,
            fixtures: [],
            emitter: new EventEmitter()
        };

        // API Calls
        async function fetchCountries() {
            try {
                const response = await fetch('/api/countries');
                store.countries = await response.json();
                store.emitter.emit('countriesUpdated', store.countries);
            } catch (error) {
                console.error('Ülkeler yüklenirken hata oluştu:', error);
            }
        }

        async function fetchLeagues(countryCode) {
            try {
                const response = await fetch(`/api/countries/${countryCode}/leagues`);
                store.leagues = await response.json();
                store.emitter.emit('leaguesUpdated', store.leagues);
            } catch (error) {
                console.error('Ligler yüklenirken hata oluştu:', error);
            }
        }

        async function fetchFixtures(leagueId) {
            try {
                const response = await fetch(`/api/leagues/${leagueId}/fixtures`);
                store.fixtures = await response.json();
                store.emitter.emit('fixturesUpdated', store.fixtures);
            } catch (error) {
                console.error('Maçlar yüklenirken hata oluştu:', error);
            }
        }

        // UI Updates
        function displayCountries(countries) {
            const countriesList = document.getElementById('countries-list');
            countriesList.innerHTML = '';
            countries.forEach(country => {
                const countryElement = document.createElement('div');
                countryElement.className = 'flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer';
                countryElement.innerHTML = `
                    <img src="${country.flag}" alt="${country.name}" class="w-6 h-6 mr-3">
                    <span class="text-sm">${country.name}</span>
                `;
                countryElement.addEventListener('click', () => {
                    store.selectedCountry = country;
                    store.emitter.emit('countrySelected', country);
                    fetchLeagues(country.code);
                });
                countriesList.appendChild(countryElement);
            });
        }

        function displayLeagues(leagues) {
            const mainContent = document.querySelector('.flex-1');
            mainContent.innerHTML = `
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Ligler</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    ${leagues.map(league => `
                        <div class="bg-white p-4 rounded-lg shadow hover:shadow-md cursor-pointer transition-shadow"
                             onclick="handleLeagueClick(${league.league.id})">
                            <div class="flex items-center space-x-3">
                                <img src="${league.league.logo}" alt="${league.league.name}" class="w-8 h-8">
                                <div>
                                    <h3 class="font-semibold">${league.league.name}</h3>
                                    <p class="text-sm text-gray-500">${league.country.name}</p>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function displayFixtures(fixtures) {
            const mainContent = document.querySelector('.flex-1');
            mainContent.innerHTML = `
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Maçlar</h2>
                <div class="space-y-4">
                    ${fixtures.map(fixture => `
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <img src="${fixture.teams.home.logo}" alt="${fixture.teams.home.name}" class="w-6 h-6">
                                    <span>${fixture.teams.home.name}</span>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">${new Date(fixture.fixture.date).toLocaleDateString('tr-TR')}</div>
                                    <div class="font-semibold">${fixture.fixture.status.short}</div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span>${fixture.teams.away.name}</span>
                                    <img src="${fixture.teams.away.logo}" alt="${fixture.teams.away.name}" class="w-6 h-6">
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        // Event Handlers
        function handleLeagueClick(leagueId) {
            store.selectedLeague = leagueId;
            store.emitter.emit('leagueSelected', leagueId);
            fetchFixtures(leagueId);
        }

        // Event Listeners
        store.emitter.on('countriesUpdated', displayCountries);
        store.emitter.on('leaguesUpdated', displayLeagues);
        store.emitter.on('fixturesUpdated', displayFixtures);

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // İlk yükleme için ülkeleri senkronize et
            fetch('/api/countries/sync', { method: 'POST' })
                .then(() => fetchCountries())
                .catch(console.error);
        });
    </script>
</body>
</html>
