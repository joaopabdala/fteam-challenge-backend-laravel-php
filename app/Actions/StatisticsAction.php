<?php

namespace App\Actions;

use App\Factories\StoreFactory;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticsAction
{

    protected $cacheKey = 'store_statistics';


    public function execute($limit= 5)
    {
        $cacheDuration = 3600;

        return Cache::remember($this->cacheKey, $cacheDuration, function () use ($limit) {

            $products = Product::query();
            $productsCount = $products->count();
            $productsAveragePrice = $products->average('price');

            $topFiveMostExpensiveProductsSql = "
            SELECT p.id, p.title, p.price
            FROM products p
            ORDER BY p.price DESC
            LIMIT ?
            ";

            $topFiveMostExpensiveProducts = DB::select($topFiveMostExpensiveProductsSql, [$limit]);

            $totalProductsByCategoryRawSql = "
        SELECT
            c.name,
            COUNT(p.id) as total_products
        FROM
            categories c
        JOIN
            products p ON c.id = p.category_id
        GROUP BY
            c.name
        ORDER BY
            total_products DESC
    ";

            $totalByCategory = DB::select($totalProductsByCategoryRawSql);

            return [
                'totalByCategory' => $totalByCategory,
                'productsCount' => $productsCount,
                'productsAveragePrice' => $productsAveragePrice,
                'topFiveMostExpensiveProducts' => $topFiveMostExpensiveProducts
            ];
    });
    }

    public function resetCache()
    {
        Cache::delete($this->cacheKey);
    }

}
