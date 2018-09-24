<?php

declare(strict_types=1);

namespace Kreait\Personio;

class App
{
    /**
     * @var ApiClient
     */
    private $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function api(): ApiClient
    {
        return $this->client;
    }
}
