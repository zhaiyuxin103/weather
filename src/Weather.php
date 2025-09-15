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
    public const API_URL = 'https://restapi.amap.com/v3/weather/weatherInfo';

    public const TYPE_LIVE = 'live';

    public const TYPE_FORECAST = 'forecast';

    public const FORMAT_JSON = 'json';

    public const FORMAT_XML = 'xml';

    private const TYPE_MAPPING = [
        self::TYPE_LIVE     => 'base',
        self::TYPE_FORECAST => 'all',
    ];

    protected string $key;

    protected array $guzzleOptions = [];

    protected ?Client $httpClient = null;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getHttpClient(): Client
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client($this->guzzleOptions);
        }

        return $this->httpClient;
    }

    public function setGuzzleOptions(array $options): void
    {
        $this->guzzleOptions = $options;
        // Reset client instance when options change
        $this->httpClient = null;
    }

    /**
     * @throws InvalidArgumentException
     * @throws GuzzleException
     * @throws HttpException
     */
    public function getWeather(
        string $city,
        string $type = self::TYPE_LIVE,
        string $format = self::FORMAT_JSON
    ) {
        $types = self::TYPE_MAPPING;

        // 对 `$format` 进行检查，如果不是 `xml` 或 `json` 则抛出异常
        if (! in_array(strtolower($format), [self::FORMAT_XML, self::FORMAT_JSON])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format);
        }

        // 对 `$type` 进行检查，如果不是 `live` 或 `forecast` 则抛出异常
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
            $response = $this->getHttpClient()->get(self::API_URL, [
                'query' => $query,
            ])->getBody()->getContents();

            // 根据 `$format` 返回 JSON 或 XML 数据
            return $format === self::FORMAT_JSON ? json_decode($response, true) : $response;
        } catch (Exception $e) {
            // 捕获异常并抛出 HttpException
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getLiveWeather(string $city, string $format = self::FORMAT_JSON)
    {
        return $this->getWeather($city, self::TYPE_LIVE, $format);
    }

    public function getForecastsWeather(string $city, string $format = self::FORMAT_JSON)
    {
        return $this->getWeather($city, self::TYPE_FORECAST, $format);
    }
}
