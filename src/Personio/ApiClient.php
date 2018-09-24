<?php

declare(strict_types=1);

namespace Kreait\Personio;

use GuzzleHttp\ClientInterface;
use Kreait\Personio\Error\ApiError;
use Psr\Http\Message\ResponseInterface;

class ApiClient
{
    /**
     * @var ClientInterface
     */
    private $http;

    public function __construct(ClientInterface $httpClient)
    {
        $this->http = $httpClient;
    }

    public function getEmployees()
    {
        return $this->http->requestAsync('GET', 'company/employees')
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            })->wait();
    }

    public function getEmployee($id)
    {
        return $this->http->requestAsync('GET', 'company/employees/'.$id)
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            })->wait();
    }

    public function getAttendances()
    {
        return $this->http->requestAsync('GET', 'company/attendances')
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            })->wait();
    }

    public function getTimeOffs()
    {
        return $this->http->requestAsync('GET', 'company/time-offs')
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            })->wait();
    }

    public function getTimeOff($id)
    {
        return $this->http->requestAsync('GET', 'company/time-offs/'.$id)
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            })->wait();
    }

    private function handleError(\Throwable $error): Error
    {
        // @todo Handle different error types
        return ApiError::because('Api Error: '.$error->getMessage(), $error);
    }
}
