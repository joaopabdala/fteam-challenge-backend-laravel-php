<?php

namespace App\DTO;

readonly class ProductDTO
{
    public function __construct(
        public int $externalId,
        public string $title,
        public string $description,
        public float $price,
        public string $categoryName,
        public string $imageUrl,
        public float $ratingRate,
        public int $ratingCount
    ) {}
}
