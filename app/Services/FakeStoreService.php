<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class FakeStoreService
{

    private $httpClient;
    private $endpoint;

    public function __construct()
    {
        $this->httpClient = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->acceptJson();
        $this->endpoint = config('store.api-url');
    }

    private function get(string $path): Response
    {
        return $this->httpClient->retry(3, 100)->get($this->endpoint . $path)->throw();
    }

    public function getAllProducts()
    {
        $response = $this->httpClient->get($this->endpoint . "/products");
        return $response->json();
    }

    public function getCategories()
    {
        $response = $this->httpClient->get($this->endpoint . "/products/categories");
        return $response->json();
    }
}
