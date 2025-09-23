<?php

declare(strict_types=1);

namespace Yuxin\Weather\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array|string getWeather(string $city, string $type = 'live', string $format = 'json') Get weather data
 * @method static array|string getLiveWeather(string $city, string $format = 'json') Get live weather data
 * @method static array|string getForecastsWeather(string $city, string $format = 'json') Get weather forecasts
 * @method static void setGuzzleOptions(array $options) Set Guzzle HTTP client options
 */
class Weather extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'weather';
    }
}
