<?php

namespace App\Events;

use App\Models\Question;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnswerEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $question;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($user_id, $question_id)
    {
        $this->user = User::findOrFail($user_id);
        $this->question = Question::findOrFail($question_id);
        $this->message = $this->user->username . ' answered your ' . $this->question->title . ' question';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return [new PrivateChannel('user-' . $this->question->user->id)];
    }

    // Specify the name of the generated notification.
    public function broadcastAs()
    {
        return 'notification-answer';
    }
}
