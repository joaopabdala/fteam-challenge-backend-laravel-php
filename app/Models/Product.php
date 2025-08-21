<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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

}
