<?php

declare(strict_types=1);

namespace Kreait\Personio;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Kreait\Personio\Error\ApiError;
use Kreait\Personio\Error\NotFound;
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

    public function getEmployees(): PromiseInterface
    {
        return $this->http->requestAsync('GET', 'company/employees')
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            });
    }

    public function getEmployee($id): PromiseInterface
    {
        return $this->http->requestAsync('GET', 'company/employees/'.$id)
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            });
    }

    public function getAttendances(): PromiseInterface
    {
        return $this->http->requestAsync('GET', 'company/attendances')
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            });
    }

    public function getTimeOffs(): PromiseInterface
    {
        return $this->http->requestAsync('GET', 'company/time-offs')
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            });
    }

    public function getTimeOff($id): PromiseInterface
    {
        return $this->http->requestAsync('GET', 'company/time-offs/'.$id)
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            });
    }

    public function get($endpoint): PromiseInterface
    {
        return $this->http->requestAsync('GET', $endpoint)
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true);
            }, function (\Throwable $error) {
                throw $this->handleError($error);
            });
    }

    private function handleError(\Throwable $error): Error
    {
        if ($error instanceof RequestException) {
            if ($response = $error->getResponse()) {
                switch ($response->getStatusCode()) {
                    case 404:
                        return NotFound::because('Not found: '.$error->getMessage(), $error);
                }
            }
        }

        return ApiError::because('Api Error: '.$error->getMessage(), $error);
    }
}
