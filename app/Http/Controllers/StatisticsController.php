<?php

namespace App\Http\Controllers;

use App\Actions\StatisticsAction;
use App\Http\Resources\StatisticsResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $statistics = (new StatisticsAction)->execute();

        return StatisticsResource::make($statistics);
    }
}
