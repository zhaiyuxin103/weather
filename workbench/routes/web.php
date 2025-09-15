<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Yuxin\Weather\Facades\Weather;

Route::get('/', function () {
    return view('welcome');
});

Route::get('weather', function () {
    $weather = Weather::getWeather('北京');

    return $weather;
});
