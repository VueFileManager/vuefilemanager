<?php
namespace Domain\Notifications\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestUpdate implements ShouldBroadcast
{
    public string $test = 'I am tru man';

    use Dispatchable;

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('test.6474ef97-472b-43f3-b607-f71df9d33e43');
    }
}
