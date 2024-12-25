<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveMatchUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $type;
    public string $message;
    public array $match;

    /**
     * Create a new event instance.
     */
    public function __construct(string $type, string $message, array $match)
    {
        $this->type = $type;
        $this->message = $message;
        $this->match = $match;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('matches'),
            new Channel("match.{$this->match['id']}"),
            new Channel("league.{$this->match['league']['id']}")
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
            'match' => [
                'id' => $this->match['id'],
                'league' => [
                    'id' => $this->match['league']['id'],
                    'name' => $this->match['league']['name']
                ],
                'home_team' => [
                    'name' => $this->match['home_team']['name'],
                    'score' => $this->match['score']['home']
                ],
                'away_team' => [
                    'name' => $this->match['away_team']['name'],
                    'score' => $this->match['score']['away']
                ],
                'status' => $this->match['status'],
                'minute' => $this->match['minute'] ?? null,
                'details' => $this->match['details'] ?? null
            ]
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'match.update';
    }
}
