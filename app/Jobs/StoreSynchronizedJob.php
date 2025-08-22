<?php

namespace App\Jobs;

use App\Actions\StoreSynchronizeAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StoreSynchronizedJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new StoreSynchronizeAction)->execute();
    }
}
