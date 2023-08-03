<?php

namespace ReesMcIvor\GravityForms\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ReesMcIvor\GravityForms\Models\GravityFormEntry;

class GravityFormEntryCreateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public GravityFormEntry $gravityFormEntry;

    public function __construct( $gravityFormEntry )
    {
        $this->gravityFormEntry = $gravityFormEntry;
    }
}
