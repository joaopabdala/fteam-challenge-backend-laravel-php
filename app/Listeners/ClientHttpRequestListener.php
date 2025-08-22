<?php

namespace App\Listeners;

use App\Support\HttpLogger;

/**
 *
 * based on https://github.com/danilopinotti/laravel-logs-example
 *
 */

class ClientHttpRequestListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        (new HttpLogger())
            ->logOutbound($event->request, $event->response ?? null);
    }
}
