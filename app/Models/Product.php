<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public const CACHE_TAG = 'products_list';

    protected $table = 'products';
    protected $fillable = [
        'external_id',
        'category_id',
        'title',
        'description',
        'price',
        'image',
        'rating_rate',
        'rating_count',
    ];

    protected $casts = [
        'price' => 'float'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $query->when($filters['category_id'] ?? null, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        });

        $query->when($filters['min_price'] ?? null, function ($query, $price) {
            $query->where('price', '>=', $price);
        });

        $query->when($filters['max_price'] ?? null, function ($query, $price) {
            $query->where('price', '<=', $price);
        });

        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->where('title', 'like', '%' . $title . '%');
        });

        return $query;
    }

}
