@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Canlı Maçlar -->
    @if($liveMatches->isNotEmpty())
    <div>
        <div class="flex items-center space-x-2 mb-4">
            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
            <h2 class="text-xl font-bold">Canlı Maçlar</h2>
        </div>
        <div class="space-y-4">
            @foreach($liveMatches as $match)
                <x-match-card :match="$match" />
            @endforeach
        </div>
    </div>
    @endif

    <!-- Günün Maçları -->
    @if($todayMatches->isNotEmpty())
    <div>
        <h2 class="text-xl font-bold mb-4">Günün Maçları</h2>
        <div class="space-y-4">
            @foreach($todayMatches as $match)
                <x-match-card :match="$match" />
            @endforeach
        </div>
    </div>
    @endif

    <!-- Biten Maçlar -->
    @if($finishedMatches->isNotEmpty())
    <div>
        <h2 class="text-xl font-bold mb-4">Biten Maçlar</h2>
        <div class="space-y-4">
            @foreach($finishedMatches as $match)
                <x-match-card :match="$match" />
            @endforeach
        </div>
    </div>
    @endif

    <!-- Programdaki Maçlar -->
    @if($scheduledMatches->isNotEmpty())
    <div>
        <h2 class="text-xl font-bold mb-4">Programdaki Maçlar</h2>
        <div class="space-y-4">
            @foreach($scheduledMatches as $match)
                <x-match-card :match="$match" />
            @endforeach
        </div>
    </div>
    @endif

    <!-- Veri Yoksa -->
    @if($liveMatches->isEmpty() && $todayMatches->isEmpty() && $finishedMatches->isEmpty() && $scheduledMatches->isEmpty())
    <div class="text-center py-12">
        <i class="fas fa-futbol text-4xl text-gray-600 mb-4"></i>
        <p class="text-gray-400">Bu tarih için maç bulunamadı.</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Canlı maçları her 60 saniyede bir güncelle
    setInterval(() => {
        if (document.querySelector('.animate-pulse')) {
            window.livewire.emit('refreshLiveMatches');
        }
    }, 60000);

    // Maç oranlarını getir
function getMatchOdds(matchId) {
        // matchId'yi sayıya çevir
        matchId = parseInt(matchId, 10);

        // ID'nin geçerli bir sayı olduğundan emin ol
        if (isNaN(matchId) || matchId <= 0) {
            console.error('Geçersiz maç ID:', matchId);
            return;
        }
        const oddsDiv = document.getElementById(`odds-${matchId}`);
        const button = oddsDiv.previousElementSibling.querySelector('button');
        const loadingIcon = '<i class="fas fa-spinner fa-spin"></i>';
        const originalContent = button.innerHTML;
        
        // Div görünür değilse, oranları getir ve göster
        if (oddsDiv.classList.contains('hidden')) {
            // Yükleniyor durumunu göster
            button.innerHTML = loadingIcon + ' Yükleniyor...';
            button.disabled = true;
            
            fetch(`/matches/${matchId}/odds`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Oranlar alınamadı');
                    }
                    return response.json();
                })
                .then(data => {
                    // Oranları güncelle
                    const homeOdds = oddsDiv.querySelector('.odds-home');
                    const drawOdds = oddsDiv.querySelector('.odds-draw');
                    const awayOdds = oddsDiv.querySelector('.odds-away');

                    if (data && data.length > 0) {
                        homeOdds.textContent = data[0].home || '-';
                        drawOdds.textContent = data[0].draw || '-';
                        awayOdds.textContent = data[0].away || '-';
                        
                        // Div'i göster
                        oddsDiv.classList.remove('hidden');
                        button.innerHTML = '<i class="fas fa-times"></i> Oranları Gizle';
                    } else {
                        throw new Error('Bu maç için oran bulunamadı');
                    }
                })
                .catch(error => {
                    console.error('Oranlar alınırken hata oluştu:', error);
                    // Kullanıcıya hata mesajı göster
                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'text-red-500 text-sm mt-2';
                    errorMessage.textContent = error.message || 'Oranlar alınırken bir hata oluştu';
                    oddsDiv.parentNode.insertBefore(errorMessage, oddsDiv.nextSibling);
                    
                    // 3 saniye sonra hata mesajını kaldır
                    setTimeout(() => errorMessage.remove(), 3000);
                })
                .finally(() => {
                    // Butonu eski haline getir
                    if (oddsDiv.classList.contains('hidden')) {
                        button.innerHTML = originalContent;
                    }
                    button.disabled = false;
                });
        } else {
            // Div zaten görünürse, gizle
            oddsDiv.classList.add('hidden');
            button.innerHTML = '<i class="fas fa-chart-line"></i> Oranları Göster';
        }
    }
</script>
@endpush
@endsection
