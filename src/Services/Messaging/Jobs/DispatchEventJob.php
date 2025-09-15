<?php

namespace RCV\Core\Services\Messaging\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DispatchEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $event;
    public array $payload;
    public array $options;

    public int $tries = 5;
    public int $backoff = 5;

    public function __construct(string $event, array $payload = [], array $options = [])
    {
        $this->event = $event;
        $this->payload = $payload;
        $this->options = $options;
    }

    public function handle(): void
    {
        try {
            event($this->event, $this->payload);
        } catch (\Throwable $e) {
            Log::error('MessageBus dispatch failed: '.$e->getMessage(), [
                'event' => $this->event,
            ]);
            throw $e;
        }
    }
}


