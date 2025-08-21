<?php
namespace App\Adapters;

use App\Interfaces\StoreInterface;
use App\Services\FakeStoreService;

class FakeStoreAdapter implements StoreInterface
{

    protected FakeStoreService $service;

    public function __construct()
    {
        $this->service = (new FakeStoreService);
    }

    public function getAllProducts()
    {
        return $this->service->getAllProducts();
    }

    public function getProductsCategories()
    {
        return $this->service->getProductsCategories();
    }
}
