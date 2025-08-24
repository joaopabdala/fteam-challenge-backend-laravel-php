<?php

/**
 * declares strict_types for DTO typings
 */
declare(strict_types=1);

namespace App\Adapters;

use App\DTO\CategoryDTO;
use App\DTO\ProductDTO;
use App\Interfaces\StoreInterface;
use App\Services\FakeStoreService;

class FakeStoreAdapter implements StoreInterface
{

    protected FakeStoreService $service;

    public function __construct(FakeStoreService $service)
    {
        $this->service = $service;
    }

    /**
     * @return ProductDTO[]
     */
    public function getAllProducts()
    {
        $products =  $this->service->getAllProducts();

        $productDTOs = [];
        foreach ($products as $productData) {
            $productDTOs[] = new ProductDTO(
                externalId: $productData['id'],
                title: $productData['title'],
                description: $productData['description'],
                price: $productData['price'],
                categoryName: $productData['category'],
                imageUrl: $productData['image'],
                ratingRate: $productData['rating']['rate'],
                ratingCount: $productData['rating']['count']
            );
        }

        return $productDTOs;
    }

    /**
     * @return CategoryDTO[]
     */
    public function getCategories()
    {
        $categoriesFromApi =  $this->service->getCategories();
        $categoriesDTO = [];
        foreach ($categoriesFromApi as $category) {
            $categoriesDTO[] = new CategoryDTO(
                name: $category
            );
        }

        return $categoriesDTO;
    }
}
