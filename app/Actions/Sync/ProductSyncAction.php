<?php

namespace App\Actions\Sync;

use App\DTO\ProductDTO;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Log;

class ProductSyncAction
{
/**
*
* @param ProductDTO[] $productsDTO.
*/
    public function execute(array $productsDTO)
    {
        $categoryMap = Category::pluck('id', 'name');
        foreach ($productsDTO as $product) {
            try {
                Product::updateOrCreate(
                    ['external_id' => $product->externalId],
                    [
                        'title' => $product->title,
                        'description' => $product->description,
                        'price' => $product->price,
                        'image' => $product->imageUrl,
                        'rating_rate' => $product->ratingRate,
                        'rating_count' => $product->ratingCount,
                        'category_id' => $categoryMap[$product->categoryName] ?? null
                    ]
                );
            } catch (Exception $e) {
                Log::warning(
                    "Failed to sync product with external ID: {$product['id']}. Reason: {$e->getMessage()}"
                );
                continue;
            }
        }
    }
}
