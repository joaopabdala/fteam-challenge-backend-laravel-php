<?php

namespace App\Factories;


use App\Adapters\FakeStoreAdapter;
use function config;

class StoreFactory
{
    public static function make()
    {
        $provider = config('store.provider');

        return match ($provider) {
            'fake-store-api' => new FakeStoreAdapter(),
            default => throw new \Exception("Provider '{$provider}' not supported")
        };
    }
}
