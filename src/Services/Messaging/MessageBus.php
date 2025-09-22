<?php

namespace RCV\Core\Services\Messaging;

use Illuminate\Support\Facades\Bus;
use RCV\Core\Services\Messaging\Jobs\DispatchEventJob;

class MessageBus
{
    public function publish(string $event, array $payload = [], array $options = []): void
    {
        $job = new DispatchEventJob($event, $payload, $options);
        Bus::dispatch($job);
    }
}


