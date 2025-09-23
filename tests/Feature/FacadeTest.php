<?php

declare(strict_types=1);

use Yuxin\Weather\Facades\Weather;

describe('Weather Facade', function (): void {
    test('can access weather service through facade', function (): void {
        expect(Weather::class)->toBeString();

        // Test facade class properties
        $reflection = new ReflectionClass(Weather::class);
        expect($reflection->isFinal())->toBeFalse();
        expect($reflection->isAbstract())->toBeFalse();
        expect($reflection->getParentClass()->getName())->toBe(Illuminate\Support\Facades\Facade::class);
    });

    test('facade has correct method annotations', function (): void {
        $reflection = new ReflectionClass(Weather::class);
        $docComment = $reflection->getDocComment();

        expect($docComment)->toContain('getWeather');
        expect($docComment)->toContain('getLiveWeather');
        expect($docComment)->toContain('getForecastsWeather');
        expect($docComment)->toContain('setGuzzleOptions');
    });

    test('facade extends laravel facade', function (): void {
        $facade = new Weather;
        expect($facade)->toBeInstanceOf(Illuminate\Support\Facades\Facade::class);
    });
});
