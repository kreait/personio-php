<?php

declare(strict_types=1);

namespace Kreait\Personio;

use GuzzleHttp\ClientInterface;
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
            })->wait();
    }

    public function getEmployee($id)
    {
        return $this->http->requestAsync('GET', 'company/employees/'.$id)
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            })->wait();
    }

    public function getAttendances()
    {
        return $this->http->requestAsync('GET', 'company/attendances')
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            })->wait();
    }

    public function getTimeOffs()
    {
        return $this->http->requestAsync('GET', 'company/time-offs')
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            })->wait();
    }

    public function getTimeOff($id)
    {
        return $this->http->requestAsync('GET', 'company/time-offs/'.$id)
            ->then(function (ResponseInterface $response) {
                return json_decode((string) $response->getBody(), true)['data'];
            })->wait();
    }
}
