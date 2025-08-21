<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(ProductsRequest $request)
    {

        $validated = $request->validated();

        $query = Product::with('category')->filter($validated);

        if($request->has('order_by_price')) {
            $query->orderBy('price', $request->input('order_by_price'));
        }

        $perPage = $validated['per_page'] ?? 10;

        $products = $query->paginate($perPage)->withQueryString();
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
