<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategorieResource;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return CategorieResource::collection($categories);
    }
}
