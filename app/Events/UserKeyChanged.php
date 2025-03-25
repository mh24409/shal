<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\FirebaseNotifications;

class UserKeyChanged implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;
    public User $senderId;
    public $selected_ids;
    public $online_users = [];
    public function __construct($id)
    {
        $this->senderId = $id;
        $id = $this->senderId->id;
        
    }
    public function broadcastOn()
    {
        $channelName = 'user' . $this->senderId->id;
        return new Channel($channelName);
    }
    public function broadcastWith()
    {


        return [
            'current_channel' => "100",
        ];
    }
}
