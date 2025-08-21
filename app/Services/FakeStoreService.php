<?php
namespace App\Services;

use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class FakeStoreService{

    private $httpClient;
    private $endpoint;

    public function __construct()
    {
        $this->httpClient = Http::withHeaders([
            'Content-Type' => 'application/json',
        ]);
        $this->endpoint = "https://fakestoreapi.com/";
    }

    private function get(string $path): Response
    {
        try {
            return Http::acceptJson()->get($this->endpoint . $path)->throw();
        } catch (RequestException $e) {
            throw $e;
        }
    }

    public function getAllProducts()
    {
        try{
            $response = $this->httpClient->get($this->endpoint. "/products");
        } catch (Exception $exception){
            return $exception->getMessage();
        }
        return $response->json();
    }

    public function getProductsCategories()
    {
        try{
            $response = $this->httpClient->get($this->endpoint. "/products/categories");
        } catch (Exception $exception){
            return $exception->getMessage();
        }
        return $response->json();
    }
}
