<?php

namespace App\Observers;

use App\Interfaces\MatchObserverInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LiveMatchObserver implements MatchObserverInterface
{
    public function notify(array $matches): void
    {
        foreach ($matches as $match) {
            $this->handleMatchUpdate($match);
        }
    }

    private function handleMatchUpdate(array $match): void
    {
        $cacheKey = "match_{$match['id']}";
        $previousMatch = Cache::get($cacheKey);

        if ($previousMatch) {
            $this->detectChanges($previousMatch, $match);
        }

        // Maç bilgilerini cache'e kaydet
        Cache::put($cacheKey, $match, now()->addHours(3));
    }

    private function detectChanges(array $previous, array $current): void
    {
        // Skor değişikliği kontrolü
        if ($previous['score']['home'] !== $current['score']['home'] || 
            $previous['score']['away'] !== $current['score']['away']) {
            $this->notifyScoreChange($current);
        }

        // Kart kontrolü
        if (isset($current['details']['events'])) {
            $this->checkNewEvents($previous, $current);
        }

        // Maç durumu değişikliği kontrolü
        if ($previous['status'] !== $current['status']) {
            $this->notifyStatusChange($current);
        }
    }

    private function notifyScoreChange(array $match): void
    {
        $message = sprintf(
            'GOL! %s %d - %d %s',
            $match['home_team']['name'],
            $match['score']['home'],
            $match['score']['away'],
            $match['away_team']['name']
        );

        $this->broadcastUpdate('score_change', $message, $match);
    }

    private function checkNewEvents(array $previous, array $current): void
    {
        $previousEvents = $previous['details']['events'] ?? [];
        $currentEvents = $current['details']['events'] ?? [];

        $newEvents = array_udiff($currentEvents, $previousEvents, function ($a, $b) {
            return $a['id'] <=> $b['id'];
        });

        foreach ($newEvents as $event) {
            $this->notifyNewEvent($event, $current);
        }
    }

    private function notifyNewEvent(array $event, array $match): void
    {
        $message = match ($event['type']) {
            'YELLOW_CARD' => sprintf('SARI KART! %s (%d\')', $event['player'], $event['minute']),
            'RED_CARD' => sprintf('KIRMIZI KART! %s (%d\')', $event['player'], $event['minute']),
            'SUBSTITUTION' => sprintf('DEĞİŞİKLİK! %s ← %s (%d\')', $event['player_in'], $event['player_out'], $event['minute']),
            default => null
        };

        if ($message) {
            $this->broadcastUpdate('new_event', $message, $match);
        }
    }

    private function notifyStatusChange(array $match): void
    {
        $message = match ($match['status']) {
            'FINISHED' => sprintf('MAÇ BİTTİ! %s %d - %d %s', 
                $match['home_team']['name'],
                $match['score']['home'],
                $match['score']['away'],
                $match['away_team']['name']
            ),
            'HALF_TIME' => 'DEVRE ARASI',
            'POSTPONED' => 'MAÇ ERTELENDİ',
            'CANCELLED' => 'MAÇ İPTAL EDİLDİ',
            default => null
        };

        if ($message) {
            $this->broadcastUpdate('status_change', $message, $match);
        }
    }

    private function broadcastUpdate(string $type, string $message, array $match): void
    {
        // Broadcast event'ini gönder
        broadcast(new \App\Events\LiveMatchUpdate($type, $message, $match))->toOthers();

        // Log kaydı
        Log::channel('match_updates')->info($message, [
            'type' => $type,
            'match_id' => $match['id'],
            'league' => $match['league']['name']
        ]);
    }
}
