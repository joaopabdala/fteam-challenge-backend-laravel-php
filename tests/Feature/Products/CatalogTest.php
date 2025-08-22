<?php

use App\Models\Category;
use App\Models\Product;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->categories = Category::factory()->count(3)->create();

    Product::factory()->count(15)->create([
        'category_id' => $this->categories->random()->id,
    ]);

    withHeaders(['X-Client-Id' => 'my-test-client-id']);
});

test('it can list products with pagination', function () {

    getJson('/api/products?per_page=5')
        ->assertOk() // Status 200
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'price', 'category', 'image', 'rating']
            ],
            'links',
            'meta'
        ]);
});

test('it can show a single product', function () {
    $product = Product::first();

    getJson("/api/products/{$product->id}")
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $product->id,
                'title' => $product->title,
            ]
        ]);
});

test('it returns 404 for a non-existent product', function () {
    getJson('/api/products/9999')
        ->assertNotFound();
});

test('it can filter products by category', function () {
    $targetCategory = $this->categories->first();

    Product::factory()->count(2)->create(['category_id' => $targetCategory->id]);

    getJson("/api/products?category_id={$targetCategory->id}")
        ->assertOk()
        ->assertJsonFragment(['category' => $targetCategory->name]);

    $response = getJson("/api/products?category_id={$targetCategory->id}&per_page=100");
    $products = $response->json('data');
    foreach ($products as $product) {
        expect($product['category'])->toBe($targetCategory->name);
    }
});


test('it can filter by min and max price', function () {
    Product::query()->delete();

    Product::factory()->create(['price' => 50.00]);
    Product::factory()->create(['price' => 100.00]);
    Product::factory()->create(['price' => 150.00]);

    getJson('/api/products?min_price=75.00&max_price=125.00')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['price' => 100.00]);
});


test('it can filter by title', function () {
    Product::factory()->create(['title' => 'My Awesome Laptop']);

    getJson('/api/products?title=Awesome Laptop')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['title' => 'My Awesome Laptop']);
});

test('it can sort products by price descending', function () {
    Product::factory()->create(['price' => 10.00]);
    Product::factory()->create(['price' => 90.00]);

    $response = getJson('/api/products?order_by_price=desc');

    $response->assertOk();

    $prices = collect($response->json('data'))->pluck('price')->all();

    expect($prices[0])->toBeGreaterThanOrEqual($prices[1]);
});
