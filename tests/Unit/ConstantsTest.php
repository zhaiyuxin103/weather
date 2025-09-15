<?php

declare(strict_types=1);

use Yuxin\Weather\Weather;

describe('Weather Constants', function () {
    test('has correct api url constant', function () {
        expect(Weather::API_URL)->toBe('https://restapi.amap.com/v3/weather/weatherInfo');
    });

    test('has correct type constants', function () {
        expect(Weather::TYPE_LIVE)->toBe('live');
        expect(Weather::TYPE_FORECAST)->toBe('forecast');
    });

    test('has correct format constants', function () {
        expect(Weather::FORMAT_JSON)->toBe('json');
        expect(Weather::FORMAT_XML)->toBe('xml');
    });

    test('has correct type mapping', function () {
        $reflection  = new ReflectionClass(Weather::class);
        $typeMapping = $reflection->getConstant('TYPE_MAPPING');

        expect($typeMapping)->toBe([
            'live'     => 'base',
            'forecast' => 'all',
        ]);
    });
});
