<?php
declare(strict_types=1);

namespace App\Adapters;

use App\DTO\ProductDTO;
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
        $products =  $this->service->getAllProducts();

        $productDTOs = [];
        foreach ($products as $productData) {
            $productDTOs[] = new ProductDTO(
                externalId: $productData['id'],
                title: $productData['title'],
                description: $productData['description'],
                price: (int) ($productData['price'] * 100),
                categoryName: $productData['category'],
                imageUrl: $productData['image'],
                ratingRate: $productData['rating']['rate'],
                ratingCount: $productData['rating']['count']
            );
        }
        return $productDTOs;
    }

    public function getCategories()
    {
        return $this->service->getCategories();
    }
}
