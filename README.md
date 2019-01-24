# Personio PHP SDK

A PHP SDK to work with the [Personio](https://www.personio.de/) API.

**This package is no longer maintained. Use https://github.com/jeromegamez/personio-php instead.**

[![Current version](https://img.shields.io/packagist/v/kreait/personio.svg)](https://packagist.org/packages/kreait/personio)
[![Supported PHP version](https://img.shields.io/packagist/php-v/kreait/personio.svg)]()
[![Build Status](https://travis-ci.com/kreait/personio-php.svg?branch=master)](https://travis-ci.com/kreait/personio-php)
[![GitHub license](https://img.shields.io/github/license/kreait/personio-php.svg)](https://github.com/kreait/personio-php/blob/master/LICENSE)

## Installation

```bash
composer require kreait/personio
```

## Usage

```php
use Kreait\Personio;

try {
    $app = Personio::initializeApp([
        'client_id' => getenv('PERSONIO_CLIENT_ID'),
        'client_secret' => getenv('PERSONIO_CLIENT_SECRET'),
        'debug' => false, // default
    ]);

    $employees = $app->getEmployees(); 
    $employee = $app->getEmployee($id);
    
    $timeOffs = $app->getTimeOffPeriods();
    $timeOff = $app->getTimeOffPeriod($id);
    
    
} catch (Personio\Error $e) {
    echo $e->getMessage();
}
```
