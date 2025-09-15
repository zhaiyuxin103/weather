<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use Yuxin\Weather\Weather;

describe('HTTP Client', function () {
    test('uses singleton pattern', function () {
        $weather = new Weather('mock-key');

        $client1 = $weather->getHttpClient();
        $client2 = $weather->getHttpClient();

        expect($client1)->toBe($client2);
    });

    test('can set guzzle options', function () {
        $weather = new Weather('mock-key');

        expect($weather->getHttpClient()->getConfig('timeout'))->toBeNull();

        $weather->setGuzzleOptions(['timeout' => 50000]);

        expect($weather->getHttpClient()->getConfig('timeout'))->toBe(50000);
    });

    test('creates new client when options change', function () {
        $weather = new Weather('mock-key');

        $client1 = $weather->getHttpClient();

        $weather->setGuzzleOptions(['timeout' => 30]);
        $client2 = $weather->getHttpClient();

        expect($client1)->not->toBe($client2);
        expect($client2->getConfig('timeout'))->toBe(30);
    });

    test('client is instance of guzzle client', function () {
        $weather = new Weather('mock-key');

        expect($weather->getHttpClient())->toBeInstanceOf(Client::class);
    });
});
