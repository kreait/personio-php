# Personio PHP SDK

An SDK to access the Persion API.

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
