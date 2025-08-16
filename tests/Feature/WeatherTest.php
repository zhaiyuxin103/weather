<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery\Matcher\AnyArgs;
use Yuxin\Weather\Exceptions\InvalidArgumentException;
use Yuxin\Weather\Weather;

describe('Weather', function () {
    test('get http client', function () {
        $weather = new Weather('mock-key');
        expect($weather->getHttpClient())->toBeInstanceOf(Client::class);
    });

    test('set guzzle options', function () {
        $weather = new Weather('mock-key');

        // 设置参数前，timeout 为 null
        expect($weather->getHttpClient()->getConfig('timeout'))->toBeNull();

        // 设置参数
        $weather->setGuzzleOptions(['timeout' => 50000]);

        // 设置参数后，timeout 为 50
        expect($weather->getHttpClient()->getConfig('timeout'))->toBe(50000);
    });
});

describe('Get Weather', function () {
    describe('invalid argument', function () {
        // 测试 $type 参数
        test('type', function () {
            expect(function () {
                $weather = new Weather('mock-key');
                $weather->getWeather('北京', 'foo');
            })->toThrow(InvalidArgumentException::class, 'Invalid type value(live/forecast): foo');
        });

        // 测试 $format 参数
        test('format', function () {
            expect(function () {
                $weather = new Weather('mock-key');
                $weather->getWeather('北京', 'base', 'array');
            })->toThrow(InvalidArgumentException::class, 'Invalid response format: array');
        });
    });

    describe('return', function () {
        test('json', function () {
            // 创建模拟接口响应值
            $response = new Response(200, [], '{"success": true}');

            // 创建模拟的 Guzzle HTTP 客户端
            $client = Mockery::mock(Client::class);

            // 指定将会产生的行为（在后续的测试中将会按下面的参数来调用）
            $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
                'query' => [
                    'key'        => 'mock-key',
                    'city'       => '北京',
                    'output'     => 'json',
                    'extensions' => 'base',
                ],
            ])->andReturn($response);

            // 将 `getHttpClient` 方法替换为使用模拟客户端
            $weather = Mockery::mock(Weather::class, ['mock-key'])->makePartial();
            // $client 为上面创建的模拟实例
            $weather->allows()->getHttpClient()->andReturn($client);

            // 然后调用 `getWeather` 方法，并断言返回值为模拟的返回值
            expect($weather->getWeather('北京'))->toBe(['success' => true]);
        });

        test('xml', function () {
            $response = new Response(200, [], '<hello>world</hello>');

            $client = Mockery::mock(Client::class);

            $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
                'query' => [
                    'key'        => 'mock-key',
                    'city'       => '北京',
                    'output'     => 'xml',
                    'extensions' => 'all',
                ],
            ])->andReturn($response);

            $weather = Mockery::mock(Weather::class, ['mock-key'])->makePartial();
            $weather->allows()->getHttpClient()->andReturn($client);

            expect($weather->getWeather('北京', 'forecast', 'xml'))->toBe('<hello>world</hello>');
        });
    });

    describe('exception', function () {
        test('guzzle runtime', function () {
            // 接着需要断言调用时会产生异常
            expect(function () {
                $client = Mockery::mock(Client::class);
                $client->allows()->get(new AnyArgs)->andThrow(new Exception('request timeout'));

                $weather = Mockery::mock(Weather::class, ['mock-key'])->makePartial();
                $weather->allows()->getHttpClient()->andReturn($client);

                $weather->getWeather('北京');
            })->toThrow(Exception::class, 'request timeout');
        });
    });

    test('get live weather', function () {
        // 将 getWeather 接口模拟为返回固定内容，以测试参数传递是否正确
        $weather = Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $weather->expects()->getWeather('北京', 'live', 'json')->andReturn(['success' => true]);

        expect($weather->getLiveWeather('北京'))->toBe(['success' => true]);
    });

    test('get forecasts weather', function () {
        $weather = Mockery::mock(Weather::class, ['mock-key'])->makePartial();
        $weather->expects()->getWeather('北京', 'forecast', 'json')->andReturn(['success' => true]);

        expect($weather->getForecastsWeather('北京'))->toBe(['success' => true]);
    });
});
