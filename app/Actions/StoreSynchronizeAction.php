<?php

namespace App\Actions;

use App\Factories\StoreFactory;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class StoreSynchronizeAction
{
    public function execute()
    {
        try {
            Log::info('Starting synchronization with the Fake Store API...');
            $store = StoreFactory::make();
            $categoriesFromApi = $store->getCategories();

            foreach ($categoriesFromApi as $categoryName) {
                try {
                    Category::updateOrCreate(
                        ['name' => $categoryName],
                        []
                    );
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }

            $productsFromApi = $store->getAllProducts();
            $categoryMap = Category::pluck('id', 'name');

            foreach ($productsFromApi as $product) {
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

            $productCount = count($productsFromApi);
            $categoryCount = count($categoriesFromApi);
            Log::info("Synchronization completed successfully. {$productCount} products and {$categoryCount} categories processed.");

        } catch (ConnectionException $e) {
            Log::error('Timeout or connection failure when trying to access the Fake Store API.', ['error' => $e->getMessage()]);
            throw new \Exception('The external service (Fake Store API) is unavailable.', 503);

        } catch (\Exception $e) {
            Log::error('Unknown error while synchronizing with the Fake Store API: ' . $e->getMessage());
            throw new \Exception('An internal error occurred during synchronization.', 500);
        }
    }
}
