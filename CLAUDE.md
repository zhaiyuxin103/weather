# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP weather API library for Laravel that integrates with Amap (高德地图) weather API. It provides a simple interface for fetching weather information including live weather data and forecasts for Chinese cities.

## Development Commands

### Testing

- Run all tests: `./vendor/bin/pest`
- Run specific test file: `./vendor/bin/pest tests/Feature/WeatherTest.php`
- Generate coverage report: `./vendor/bin/pest --coverage`

### Code Quality

- Lint code: `./vendor/bin/pint`
- Static analysis: `./vendor/bin/phpstan`
- Format code: `./vendor/bin/pint --ansi`

### Package Development

- Clear cache: `composer clear`
- Prepare environment: `composer prepare`
- Build workbench: `composer build`
- Serve workbench: `composer serve`

## Architecture

### Core Components

**Weather Class** (`src/Weather.php`)

- Main service class that handles API communication with Amap
- Uses Guzzle HTTP client with singleton pattern for performance
- Defines constants for API URL, types, and formats
- Provides methods: `getWeather()`, `getLiveWeather()`, `getForecastsWeather()`
- Validates parameters and throws custom exceptions

**Weather Facade** (`src/Facades/Weather.php`)

- Laravel Facade for easy access to Weather service
- Supports all Weather class methods through static interface
- Auto-registered via composer.json Laravel package discovery

**Service Provider** (`src/ServiceProvider.php`)

- Laravel service provider for dependency injection
- Registers Weather class as singleton
- Implements DeferrableProvider for lazy loading
- Binds to `config('services.weather.key')` for API key

**Exception Handling**

- `InvalidArgumentException`: Invalid parameter validation
- `HttpException`: HTTP request failures (wraps Guzzle exceptions)

### API Integration

The library communicates with Amap's weather API at `https://restapi.amap.com/v3/weather/weatherInfo` with these parameters:

- `key`: API key from environment
- `city`: City name or code
- `output`: Response format (json/xml)
- `extensions`: Weather type (base/live for current, all/forecast for forecasts)

### Testing Structure

- Uses Pest PHP testing framework
- Comprehensive test coverage including:
  - `tests/Feature/WeatherTest.php`: Core API functionality
  - `tests/Feature/FacadeTest.php`: Laravel Facade integration
  - `tests/Unit/ConstantsTest.php`: Constant definitions
  - `tests/Unit/HttpClientTest.php`: HTTP client singleton pattern
- Mocks Guzzle client for isolated testing
- Service provider integration tests

### Laravel Integration

- Supports dependency injection in controllers
- Configuration via `config/services.php`
- Environment variable: `WEATHER_API_KEY`
- Works as both standalone class and Laravel service

### Code Style

- Strict types declaration enforced
- PSR-4 autoloading: `Yuxin\Weather\` from `src/`
- Laravel Pint configuration with strict rules
- PHPStan static analysis at level 5
- Ordered class elements and imports enforced
