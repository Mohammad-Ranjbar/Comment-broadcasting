<?php

namespace App\Events;

use App\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use function MongoDB\BSON\toJSON;

class NewComment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('post.' . $this->comment->post->id);
    }

    public function broadcastWith()
    {
        return [
            'body'       => $this->comment->body,
            'parent_id'  => $this->comment->parent_id,
            'created_at' => $this->comment->created_at,
            'updated_at' => $this->comment->updated_at,
            'user'       => [
                'name'   => $this->comment->user->name,
                'avatar' => 'http://lorempixel/50/50',
            ],
        ];
    }
}
