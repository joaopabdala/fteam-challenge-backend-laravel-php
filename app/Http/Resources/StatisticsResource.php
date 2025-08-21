<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'products_count' => $this['productsCount'],
            'average_price' => number_format($this['productsAveragePrice'], 2, '.', ''),
            'count_by_category' => $this['totalByCategory'],
            'top_expensive_products' => $this['topFiveMostExpensiveProducts'],
        ];
    }
}
