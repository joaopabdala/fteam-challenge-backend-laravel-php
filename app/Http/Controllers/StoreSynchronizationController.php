<?php

namespace App\Http\Controllers;

use App\Actions\StoreSynchronizeAction;
use Illuminate\Http\Request;

class StoreSynchronizationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        (new StoreSynchronizeAction)->execute();

        return response()->noContent();
    }
}
