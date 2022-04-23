<?php

namespace Domain\Files\Events;

use Domain\Files\Resources\FileResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewFileWasStoredEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public FileResource $file,
    ) {}

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'file.created';
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("App.Users.Models.User.{$this->file->user_id}");
    }
}
