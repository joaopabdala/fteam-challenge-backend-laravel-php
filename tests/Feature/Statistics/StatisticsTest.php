<?php

use App\Models\Category;
use App\Models\Product;
use App\Utils\FormatHelper;

use function Pest\Laravel\withHeaders;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('it returns the correct statistics report', function () {
    $category1 = Category::factory()->create(['name' => 'eletronics']);
    $category2 = Category::factory()->create(['name' => "men's clothing"]);

    Product::factory()->create(['category_id' => $category1->id, 'price' => 10000]);
    Product::factory()->create(['category_id' => $category1->id, 'price' => 20000]);

    Product::factory()->create(['category_id' => $category2->id, 'price' => 30000]);

    $expectedTotalProducts = 3;
    $expectedAveragePrice = (10000 + 20000 + 30000) / 3;
    $expectedFormattedAverage = FormatHelper::currencyFormat($expectedAveragePrice);

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
        ->assertJsonPath('data.top_expensive_products.0.price', FormatHelper::currencyFormat(30000));
});
