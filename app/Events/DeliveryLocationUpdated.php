<?php

namespace App\Events;

use App\Models\Delivery;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// here any implements from ShouldBroadcast laravel directly understood mean send events to BroadcastServices mean pusher
class DeliveryLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // here I put public because sent to Broadcast service here with our pusher
    public $lat;
    public $lng;
    protected $delivery;

    /**
     * Create a new event instance.
     */
    public function __construct(Delivery $delivery, $lat, $lng)
    {
        $this->delivery - $delivery;
        $this->lat = (float) $lat;
        $this->lng = (float) $lng;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // here this's name of channel that will sent to it
        return [
            // private channel must make authentication
            // new PrivateChannel('channel-name'),
            //this public channel mean not condition make authentication
            new PrivateChannel('deliveries' . $this->delivery->order_id),
        ];
    }

    // this's if I need sent data specially
    public function broadcastWith()
    {
        // here determine send the data
        return [
            'lat' => $this->lat,
            'lng' => $this->lng,
        ];
    }

    public function broadcastAs()
    {
        // to change name of event
        return 'location-updated';
    }
}
