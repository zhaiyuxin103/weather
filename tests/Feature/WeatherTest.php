<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use Yuxin\Weather\Exceptions\InvalidArgumentException;
use Yuxin\Weather\Weather;

describe('Weather', function (): void {
    test('get http client', function (): void {
        $weather = new Weather('mock-key');
        expect($weather->getHttpClient())->toBeInstanceOf(Client::class);
    });

    test('set guzzle options', function (): void {
        $weather = new Weather('mock-key');

        // 设置参数前，timeout 为 null
        expect($weather->getHttpClient()->getConfig('timeout'))->toBeNull();

        // 设置参数
        $weather->setGuzzleOptions(['timeout' => 50000]);

        // 设置参数后，timeout 为 50
        expect($weather->getHttpClient()->getConfig('timeout'))->toBe(50000);
    });

    describe('Get Weather invalid arguments', function (): void {
        test('type', function (): void {
            expect(function (): void {
                $weather = new Weather('mock-key');
                $weather->getWeather('北京', 'foo');
            })->toThrow(InvalidArgumentException::class, 'Invalid type value(live/forecast): foo');
        });

        test('format', function (): void {
            expect(function (): void {
                $weather = new Weather('mock-key');
                $weather->getWeather('北京', 'base', 'array');
            })->toThrow(InvalidArgumentException::class, 'Invalid response format: array');
        });
    });

    test('get live weather delegates to getWeather', function (): void {
        $weather = new class('mock-key') extends Weather
        {
            public function getWeather(string $city, string $type = 'live', string $format = 'json')
            {
                if ($city === '北京' && $type === 'live' && $format === 'json') {
                    return ['success' => true];
                }

                return parent::getWeather($city, $type, $format);
            }
        };

        $result = $weather->getLiveWeather('北京');
        expect($result)->toBe(['success' => true]);
    });

    test('get forecasts weather delegates to getWeather', function (): void {
        $weather = new class('mock-key') extends Weather
        {
            public function getWeather(string $city, string $type = 'live', string $format = 'json')
            {
                if ($city === '北京' && $type === 'forecast' && $format === 'json') {
                    return ['success' => true];
                }

                return parent::getWeather($city, $type, $format);
            }
        };

        $result = $weather->getForecastsWeather('北京');
        expect($result)->toBe(['success' => true]);
    });

    test('constants have correct values', function (): void {
        expect(Weather::API_URL)->toBe('https://restapi.amap.com/v3/weather/weatherInfo');
        expect(Weather::TYPE_LIVE)->toBe('live');
        expect(Weather::TYPE_FORECAST)->toBe('forecast');
        expect(Weather::FORMAT_JSON)->toBe('json');
        expect(Weather::FORMAT_XML)->toBe('xml');
    });

    test('weather class has required constants', function (): void {
        expect(Weather::TYPE_LIVE)->toBe('live');
        expect(Weather::TYPE_FORECAST)->toBe('forecast');
        expect(Weather::FORMAT_JSON)->toBe('json');
        expect(Weather::FORMAT_XML)->toBe('xml');
    });
});
