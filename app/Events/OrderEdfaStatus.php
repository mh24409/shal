<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\FirebaseNotifications;
use App\Models\Order;

class OrderEdfaStatus implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;
    public Order $order;
    public $selected_ids;
    public $online_users = [];
    public function __construct($order)
    {
        $this->order = $order;
        $id = $this->order->id;
    }
    public function broadcastOn()
    {
        $channelName = 'order' . $this->order->id;
        return new Channel($channelName);
    }
    public function broadcastWith()
    {
        return [
            'payment_status' => $this->order->payment_status,
            'order_id' => $this->order->id,
        ];
    }
}
