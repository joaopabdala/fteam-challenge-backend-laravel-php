<?php

use App\Models\Category;
use App\Models\Product;

use Illuminate\Support\Facades\Log;
use function Pest\Laravel\withHeaders;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('it returns the correct statistics report', function () {
    $category1 = Category::factory()->create(['name' => 'eletronics']);
    $category2 = Category::factory()->create(['name' => "men's clothing"]);

    Product::factory()->create(['category_id' => $category1->id, 'price' => 100.00]);
    Product::factory()->create(['category_id' => $category1->id, 'price' => 200.00]);

    $mostExpensiveProduct = Product::factory()->create(['category_id' => $category2->id, 'price' => 300.00]);

    Log::error('mostexpensive: ' . $mostExpensiveProduct);

    $expectedTotalProducts = 3;
    $expectedAveragePrice = (100.00 + 200.00 + 300.00) / $expectedTotalProducts;
    $expectedFormattedAverage = number_format($expectedAveragePrice, 2, '.', '');

    $response = withHeaders(['X-Client-Id' => 'my-test-client-id'])->getJson('/api/statistics');

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'products_count',
                'average_price',
                'count_by_category',
                'top_expensive_products',
            ]
        ])
        ->assertJsonPath('data.products_count', $expectedTotalProducts)
        ->assertJsonPath('data.average_price', $expectedFormattedAverage)
        ->assertJsonCount(2, 'data.count_by_category')
        ->assertJsonPath('data.top_expensive_products.0.price',  '300.00');
});
