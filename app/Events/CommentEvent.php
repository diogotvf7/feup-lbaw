<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $comment;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($user_id, $comment_id)
    {
        $this->user = User::findOrFail($user_id);
        $this->comment = Comment::findOrFail($comment_id);

        $this->message = ($this->user->username) . ' commented on your '. strtolower($this->comment->type);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return [new PrivateChannel('user-' . $this->comment->content->user->id)];
    }

    // Specify the name of the generated notification.
    public function broadcastAs()
    {
        return 'notification-comment';
    }
}
