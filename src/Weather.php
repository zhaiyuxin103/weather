<?php

namespace Yuxin\Weather;

use GuzzleHttp\Client;

class Weather
{
    protected string $key;
    protected array $guzzleOptions = [];
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    public function getWeather($city, string $type = 'base', string $format = 'json')
    {
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';

        $query = array_filter([
            'key' => $this->key,
            'city' => $city,
            'output' => $format,
            'extensions' => $type,
        ]);

        $response = $this->getHttpClient()->get($url, [
            'query' => $query,
        ])->getBody()->getContents();

        return 'json' === $format ? json_decode($response, true) : $response;
    }
}