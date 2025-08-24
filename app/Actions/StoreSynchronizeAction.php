<?php

namespace App\Actions;

use App\Actions\Sync\CategorySyncAction;
use App\Actions\Sync\ProductSyncAction;
use App\Factories\StoreFactory;
use App\Models\Product;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class StoreSynchronizeAction
{
    public function execute()
    {
        try {
            Log::info('Starting synchronization with the Fake Store API...');
            $startTime = microtime(true);
            $store = StoreFactory::make();

            $categoriesFromApi = $store->getCategories();
            (new CategorySyncAction)->execute($categoriesFromApi);

            $productsFromApi = $store->getAllProducts();
            (new ProductSyncAction)->execute($productsFromApi);

            $this->cleanCache();

            $productCount = count($productsFromApi);
            $categoryCount = count($categoriesFromApi);
            $endTime = microtime(true);
            $duration = ($endTime - $startTime) * 1000;
            Log::info("Synchronization completed successfully. {$productCount} products and {$categoryCount} categories processed in " . round($duration, 2) . " ms.");

        } catch (ConnectionException $e) {
            Log::error('Timeout or connection failure when trying to access the Fake Store API.', ['error' => $e->getMessage()]);
            throw new \Exception('The external service (Fake Store API) is unavailable.', 503);

        }catch (RequestException $e) {
            Log::error('External API returned an error: ' . $e->response->status(), ['error' => $e->getMessage()]);

            if ($e->response->status() >= 400 && $e->response->status() < 500) {
                throw new \Exception('An error occurred with the external API data.', 400);
            } else {
                throw new \Exception('An internal error occurred during synchronization.', 500);
            }
        } catch (\Exception $e) {
            Log::error('Unknown error while synchronizing with the Fake Store API: ' . $e->getMessage());
            throw new \Exception('An internal error occurred during synchronization.', 500);
        }
    }

    private function cleanCache()
    {
        (new StatisticsAction)->resetCache();
        Cache::tags([Product::CACHE_TAG])->flush();
    }
}
