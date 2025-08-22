<?php

namespace App\Http\Controllers;

use App\Actions\StoreSynchronizeAction;
use App\Jobs\StoreSynchronizedJob;
use Illuminate\Http\Request;

class StoreSynchronizationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        StoreSynchronizedJob::dispatch();

        return response()->noContent();
    }
}
