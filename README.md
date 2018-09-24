# Personio PHP SDK

An SDK to access the Persion API.

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
    ]);

    $employees = $app->api()->getEmployees();
    $employee = $app->api()->getEmployee($employeeId);
    $attendances = $app->api()->getAttendances();
    $timeOffs = $app->api()->getTimeOffs();
    $timeOff = $app->api()->getTimeOff($timeOffId);
} catch (Personio\Error $e) {
    echo $e->getMessage();
}
```
