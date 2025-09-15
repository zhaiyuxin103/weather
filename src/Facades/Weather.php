<?php

declare(strict_types=1);

namespace Yuxin\Weather\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getWeather(string $city, string $type = 'live', string $format = 'json')
 * @method static array getLiveWeather(string $city, string $format = 'json')
 * @method static array getForecastsWeather(string $city, string $format = 'json')
 * @method static void setGuzzleOptions(array $options)
 */
class Weather extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'weather';
    }
}
