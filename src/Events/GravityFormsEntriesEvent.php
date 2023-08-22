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
use Illuminate\Support\Collection;

class GravityFormsEntriesEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collection $gravityFormEntries;

    public function __construct( $gravityFormEntries )
    {
        $this->gravityFormEntries = $gravityFormEntries;
    }
}
