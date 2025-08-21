<?php

namespace App\Http\Controllers;

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
        $products = Product::query();
        $productsCount = $products->count();
        $productsAveragePrice = $products->average('price');

        $sql = "lasdkfjl"
        $topFiveMostExpensiveProducts = DB::select($sql);


        $totalByCategory = DB::table('categories')
            ->select('categories.name', DB::raw('COUNT(products.id) as total_products'))
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->groupBy('categories.name')
            ->orderBy('total_products', 'desc')
            ->get();

        dd($totalByCategory);
    }
}
