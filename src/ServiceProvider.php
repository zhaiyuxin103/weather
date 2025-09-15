<?php

declare(strict_types=1);

namespace Yuxin\Weather;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * 标记着提供器是延迟加载的
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/services.php',
            'services'
        );

        $this->publishes([
            __DIR__ . '/../config/services.php' => config_path('services.php'),
        ], 'weather-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                // Commands can be added here
            ]);
        }
    }

    public function register()
    {
        $this->app->singleton(Weather::class, function () {
            return new Weather(config('services.weather.key'));
        });

        $this->app->alias(Weather::class, 'weather');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [Weather::class];
    }
}
