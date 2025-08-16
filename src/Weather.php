<?php

declare(strict_types=1);

namespace Yuxin\Weather;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yuxin\Weather\Exceptions\HttpException;
use Yuxin\Weather\Exceptions\InvalidArgumentException;

class Weather
{
    protected string $key;

    protected array $guzzleOptions = [];

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getHttpClient(): Client
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options): void
    {
        $this->guzzleOptions = $options;
    }

    /**
     * @throws InvalidArgumentException
     * @throws GuzzleException
     * @throws HttpException
     */
    public function getWeather($city, string $type = 'live', string $format = 'json')
    {
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';

        $types = [
            'live'     => 'base',
            'forecast' => 'all',
        ];

        // 对 `$format` 进行检查，如果不是 `xml` 或 `json` 则抛出异常
        if (! in_array(strtolower($format), ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format);
        }

        // 对 `$type` 进行检查，如果不是 `base` 或 `all` 则抛出异常
        if (! array_key_exists(strtolower($type), $types)) {
            throw new InvalidArgumentException('Invalid type value(live/forecast): ' . $type);
        }

        // 构建查询参数
        $query = array_filter([
            'key'        => $this->key,
            'city'       => $city,
            'output'     => $format,
            'extensions' => $types[$type],
        ]);

        try {
            // 调用 getHttpClient 发送 HTTP 请求并返回结果
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            // 根据 `$format` 返回 JSON 或 XML 数据
            return $format === 'json' ? json_decode($response, true) : $response;
        } catch (Exception $e) {
            // 捕获异常并抛出 HttpException
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getLiveWeather($city, string $format = 'json')
    {
        return $this->getWeather($city, 'live', $format);
    }

    public function getForecastsWeather($city, string $format = 'json')
    {
        return $this->getWeather($city, 'forecast', $format);
    }
}
