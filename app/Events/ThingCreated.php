<?php

namespace App\Events;

use App\Models\Thing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThingCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $thing;

    public function __construct(Thing $thing)
    {
        $this->thing = $thing->load(['place', 'master']);
        \Log::info('Событие ThingCreated отправлено! Название вещи: ' . $thing->name);
    }

    public function broadcastOn()
    {
        return new Channel('things'); // публичный канал — всем видно
    }

    public function broadcastAs()
    {
        return 'thing.created';
    }
}
