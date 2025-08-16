# Weather - 高德天气 API 组件

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zhaiyuxin/weather?style=for-the-badge)](https://packagist.org/packages/zhaiyuxin/weather)
[![Total Downloads on Packagist](https://img.shields.io/packagist/dt/zhaiyuxin/weather?style=for-the-badge)](https://packagist.org/packages/zhaiyuxin/weather)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=for-the-badge)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/zhaiyuxin103/weather/tests.yml?style=for-the-badge
)](https://github.com/zhaiyuxin103/weather/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/zhaiyuxin103/weather?style=for-the-badge)](https://codecov.io/gh/zhaiyuxin103/weather)

一个基于高德开放平台 API 的 PHP 天气信息组件，支持 Laravel 框架，提供简单易用的天气查询功能。
****

## ✨ 特性

- 🌤️ 支持实时天气查询
- 🏙️ 支持全国城市天气信息
- 📱 完美集成 Laravel 框架
- 🔧 支持 JSON 和 XML 响应格式
- 🚀 基于 Guzzle HTTP 客户端
- 🧪 完整的测试覆盖
- 📚 详细的文档和示例

## 📋 系统要求

- PHP >= 8.0
- Laravel >= 10.0
- Composer

## 🚀 安装

### 通过 Composer 安装

```bash
composer require zhaiyuxin/weather
```

## ⚙️ 配置

### 1. 获取高德开放平台 API Key

首先，你需要在 [高德开放平台](https://lbs.amap.com/) 注册账号并创建应用，获取 API Key。

### 2. 环境变量配置

在 `.env` 文件中添加：

```env
WEATHER_API_KEY=your_amap_api_key_here
```

### 3. 配置文件

配置文件位于 `config/services.php`：

```php
'weather' => [
    'key' => env('WEATHER_API_KEY'),
],
```

## 📖 使用方法

### 基本用法

#### 1. 直接实例化

```php
<?php

use Yuxin\Weather\Weather;

// 创建实例
$weather = new Weather('your_api_key');

// 获取基础天气信息
$response = $weather->getWeather('北京');

// 获取详细天气信息
$response = $weather->getWeather('北京', 'all');

// 获取 XML 格式响应
$response = $weather->getWeather('北京', 'base', 'xml');
```

#### 2. Laravel 中使用

```php
<?php

namespace App\Http\Controllers;

use Yuxin\Weather\Weather;

class WeatherController extends Controller
{
    public function index(Weather $weather)
    {
        // 获取北京天气
        $weather = $weather->getWeather('北京');
        
        return view('weather.index', compact('weather'));
    }
}
```

#### 3. 使用 Facade (Laravel)

```php
<?php

use Yuxin\Weather\Facades\Weather;

// 获取天气信息
$weather = Weather::getWeather('广州');
```

### API 参数说明

#### `getWeather($city, $type, $format)`

| 参数 | 类型 | 必填 | 默认值 | 说明 |
|------|------|------|--------|------|
| `$city` | string | 是 | - | 城市名称或编码 |
| `$type` | string | 否 | base | 天气类型：base（实况天气） 或 all（预报天气） |
| `$format` | string | 否 | json | 响应格式：json 或 xml |

### 响应格式

#### JSON 格式 (默认)

```json
{
    "status": "1",
    "count": "1",
    "info": "OK",
    "infocode": "10000",
    "forecasts": [
        {
            "city": "北京",
            "adcode": "110100",
            "province": "北京市",
            "reporttime": "2025-01-20 10:00:00",
            "casts": [
                {
                    "date": "2025-01-20",
                    "week": "1",
                    "dayweather": "晴",
                    "nightweather": "晴",
                    "daytemp": "5",
                    "nighttemp": "-5",
                    "daywind": "北风",
                    "nightwind": "北风",
                    "daypower": "3",
                    "nightpower": "3"
                }
            ]
        }
    ]
}
```

#### XML 格式

```xml
<?xml version="1.0" encoding="UTF-8"?>
<response>
    <status>1</status>
    <count>1</count>
    <info>OK</info>
    <infocode>10000</infocode>
    <forecasts>
        <forecast>
            <city>北京</city>
            <adcode>110100</adcode>
            <province>北京市</province>
            <reporttime>2025-01-20 10:00:00</reporttime>
            <casts>
                <cast>
                    <date>2025-01-20</date>
                    <week>1</week>
                    <dayweather>晴</dayweather>
                    <nightweather>晴</nightweather>
                    <daytemp>5</daytemp>
                    <nighttemp>-5</nighttemp>
                    <daywind>北风</daywind>
                    <nightwind>北风</nightwind>
                    <daypower>3</daypower>
                    <nightpower>3</nightpower>
                </cast>
            </casts>
        </forecast>
    </forecasts>
</response>
```

### 高级配置

#### 自定义 Guzzle 选项

```php
<?php

use Yuxin\Weather\Weather;

$weather = new Weather('your_api_key');

// 设置超时时间
$weather->setGuzzleOptions(['timeout' => 30]);

// 设置代理
$weather->setGuzzleOptions([
    'proxy' => 'http://proxy.example.com:8080',
    'timeout' => 60,
    'verify' => false
]);
```

## 🧪 测试

运行测试套件：

```bash
# 运行所有测试
./vendor/bin/pest

# 运行特定测试文件
./vendor/bin/pest tests/Feature/WeatherTest.php

# 生成测试覆盖率报告
./vendor/bin/pest --coverage
```

## 📝 异常处理

组件提供了完善的异常处理机制：

```php
<?php

use Yuxin\Weather\Weather;
use Illuminate\Support\Facades\Log;
use Yuxin\Weather\Exceptions\HttpException;
use Yuxin\Weather\Exceptions\InvalidArgumentException;

try {
    $weather = new Weather('invalid_key');
    $response = $weather->getWeather('北京');
} catch (InvalidArgumentException $e) {
    // 处理参数错误
    Log::error('参数错误: ' . $e->getMessage());
} catch (HttpException $e) {
    // 处理 HTTP 请求错误
    Log::error('请求错误: ' . $e->getMessage());
}
```

### 异常类型

- `InvalidArgumentException`: 参数验证失败
- `HttpException`: HTTP 请求异常

## 🔧 开发

### 克隆项目

```bash
git clone https://github.com/zhaiyuxin103/weather.git
cd weather
composer install
```

### 运行测试

```bash
./vendor/bin/pest
```

### 代码风格检查

```bash
./vendor/bin/pint
```

## 🤝 贡献

我们欢迎所有形式的贡献！请查看我们的 [贡献指南](CONTRIBUTING.md)。

### 贡献方式

1. 🐛 报告 Bug
2. 💡 提出新功能建议
3. 📝 改进文档
4. 🔧 提交代码修复
5. ⭐ 给项目点星

### 开发流程

1. Fork 项目
2. 创建功能分支 (`git checkout -b feature/xxx`)
3. 提交更改 (`git commit -m 'Add some feature'`)
4. 推送到分支 (`git push origin feature/xxx`)
5. 创建 Pull Request

## 📄 许可证

本项目基于 [MIT 许可证](LICENSE) 开源。

## 🙏 致谢

- [高德开放平台](https://lbs.amap.com/) - 提供天气 API 服务
- [Laravel](https://laravel.com/) - 优秀的 PHP 框架
- [Guzzle](https://docs.guzzlephp.org/) - HTTP 客户端库

## 📞 支持

如果你在使用过程中遇到问题，可以通过以下方式获取帮助：

- 📧 邮箱: zhaiyuxin103@hotmail.com
- 🐛 [GitHub Issues](https://github.com/zhaiyuxin103/weather/issues)
- 📖 [GitHub Discussions](https://github.com/zhaiyuxin103/weather/discussions)
- 🐦 Twitter: [@zhaiyuxin103](https://twitter.com/zhaiyuxin103)

## ⭐ 支持项目

如果这个项目对你有帮助，请给我们一个 ⭐️！

---

**Made with ❤️ by [YuXin Zhai](https://github.com/zhaiyuxin103)**