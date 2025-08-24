<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{
    /**
     * @queryParam category_id int Ex: 1
     * @queryParam min_price int Ex: 1000
     * @queryParam max_price int Ex: 50000
     * @queryParam title string  Ex: "Jacket"
     * @queryParam order_by_price string Ex: 'asc','desc'.
     * @queryParam per_page int Ex: 10.
     *
     * @param ProductsRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(ProductsRequest $request)
    {
        $cacheKey = 'products_list_' . md5(http_build_query($request->all()));

        $products = Cache::tags(Product::CACHE_TAG)->remember($cacheKey, now()->addMinutes(10), function () use ($request) {

            $validated = $request->validated();

            $query = Product::with('category')->filter($validated);

            if ($request->has('order_by_price')) {
                $query->orderBy('price', $request->input('order_by_price'));
            }

            $perPage = $validated['per_page'] ?? 10;

            return $query->paginate($perPage)->withQueryString();
        });

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        try {
            $product = Product::with('category')->findOrFail($id);

            return ProductResource::make($product);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }
    }
}
