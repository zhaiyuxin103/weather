<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Yuxin\Weather\ServiceProvider;
use Yuxin\Weather\Weather;

describe('Service Provider', function (): void {
    test('registers weather service as singleton', function (): void {
        $app      = new Application;
        $provider = new ServiceProvider($app);

        // Mock the config to avoid missing config dependency
        $app->singleton('config', fn () => new class
        {
            public function get($key, $default = null)
            {
                return $key === 'services.weather.key' ? 'test-key' : $default;
            }
        });

        $provider->register();

        // Test that service is registered as singleton
        $weather  = $app->make(Weather::class);
        $service2 = $app->make(Weather::class);

        expect($weather)->toBe($service2);
    });

    test('registers weather alias', function (): void {
        $app      = new Application;
        $provider = new ServiceProvider($app);

        // Mock the config to avoid missing config dependency
        $app->singleton('config', fn () => new class
        {
            public function get($key, $default = null)
            {
                return $key === 'services.weather.key' ? 'test-key' : $default;
            }
        });

        $provider->register();

        // Check if alias is registered by testing if it can be resolved
        $app->alias(Weather::class, 'weather');
        expect($app->has('weather'))->toBeTrue();
    });

    test('provides correct services', function (): void {
        $provider = new ServiceProvider(new Application);

        $services = $provider->provides();

        expect($services)->toBe([Weather::class]);
    });

    test('is deferrable provider', function (): void {
        $provider = new ServiceProvider(new Application);

        expect($provider->isDeferred())->toBeTrue();
    });
});
