<?php

namespace App\Http\Resources;

use App\Utils\FormatHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "price" => FormatHelper::currencyFormat($this->price),
            "category" => $this->category->name,
            "image" => $this->image,
            "rating" => [
                "rate" => $this->rating_rate,
                "count" => $this->rating_count
            ]

        ];
    }
}
