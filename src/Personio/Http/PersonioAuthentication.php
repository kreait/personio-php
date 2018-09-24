<?php

declare(strict_types=1);

namespace Kreait\Personio\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PersonioAuthentication
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(string $clientId, string $clientSecret, string $endpoint = null, ClientInterface $client = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->endpoint = $endpoint ?? 'https://api.personio.de/v1/auth';
        $this->client = $client ?? new Client();
    }

    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $token = $this->token ?? $this->fetchToken();

            $request = $request->withHeader('Authorization', 'Bearer '.$token);

            return $handler($request, $options)
                ->then(function (ResponseInterface $response) {
                    $header = $response->getHeaderLine('Authorization');
                    $this->token = preg_replace('/^bearer /i', '', $header);

                    return $response;
                });
        };
    }

    private function fetchToken(): string
    {
        return $this->client->postAsync($this->endpoint, [
            'query' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
        ])->then(
            function (ResponseInterface $response) {
                $data = json_decode((string) $response->getBody(), true);

                $success = $data['success'] ?? false;
                $token = $data['data']['token'] ?? null;

                if (!$success) {
                    throw new \RuntimeException('Unable to fetch authorization token.');
                }

                if (!$token) {
                    throw new \RuntimeException('Unable to get token from authorization response');
                }

                return $token;
            },
            function (\Throwable $e) {
                throw $e;
            }
        )->wait();
    }
}
