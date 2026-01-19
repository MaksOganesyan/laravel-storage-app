<?php

namespace App\Events;

use App\Models\Place;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlaceCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $place;

    public function __construct(Place $place)
    {
        $this->place = $place;
    }

    public function broadcastOn()
    {
        return new Channel('places'); // публичный канал — всем видно
    }

    public function broadcastAs()
    {
        return 'place.created';
    }
}
