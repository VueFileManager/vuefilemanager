<?php
namespace Domain\Settings\Events;

use App\Users\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TestWebsocketConnectionEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public User $user,
    ) {
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'TestWebsocketConnection';
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("App.Users.Models.User.{$this->user->id}");
    }
}
