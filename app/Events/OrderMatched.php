<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Trade;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class OrderMatched implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private Trade $trade,
        private string $userId,
        private array $payload,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<string, Channel>
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('user.'.$this->userId);
    }

    public function broadcastWith(): array
    {
        return array_merge([
            'type' => 'order.matched',
            'trade_id' => (string) $this->trade->id,
            'price' => (string) $this->trade->price,
            'amount' => (string) $this->trade->amount,
            'fee_usd' => (string) $this->trade->fee_usd,
            'timestamp' => now()->toISOString(),
        ], $this->payload);
    }
}
