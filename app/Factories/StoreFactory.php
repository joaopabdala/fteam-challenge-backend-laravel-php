<?php

namespace App\Factories;


use App\Adapters\FakeStoreAdapter;
use App\Services\FakeStoreService;
use function config;

class StoreFactory
{
    public static function make()
    {
        $provider = config('store.provider');

        return match ($provider) {
            'fake-store-api' => new FakeStoreAdapter(new FakeStoreService),
            default => throw new \Exception("Provider '{$provider}' not supported")
        };
    }
}
