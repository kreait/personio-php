<?php

declare(strict_types=1);

namespace Kreait;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Kreait\Personio\ApiClient;
use Kreait\Personio\App;
use Kreait\Personio\Error\InvalidConfiguration;
use Kreait\Personio\Http\PersonioAuthentication;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface as OptionsResolverException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Personio
{
    private const DEFAULT_APP_NAME = 'default';

    private static $apps = [];

    public static function initializeApp(array $options = null, string $name = null): App
    {
        $options = $options ?? [];
        $name = $name ?? self::DEFAULT_APP_NAME;

        if ($name === '') {
            throw InvalidConfiguration::because('Invalid app name provided. App name must be a non-empty string.');
        }

        if (array_key_exists($name, self::$apps)) {
            if (self::DEFAULT_APP_NAME === $name) {
                throw InvalidConfiguration::because(
                    'The default Personio app already exists. This means you called initializeApp() '
                    .'more than once without providing an app name as the second argument. In most cases '
                    .'you only need to call initializeApp() once. But if you do want to initialize '
                    .'multiple apps, pass a second argument to initializeApp() to give each app a unique '
                    .'name.'
                );
            }

            throw InvalidConfiguration::because(
                'A Personio app named "'.$name.'" already exists. This means you called initializeApp() '
                .'more than once with the same app name as the second argument. Make sure you provide a '
                .'unique name every time you call initializeApp().'
            );
        }

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'host' => 'https://api.personio.de',
            'endpoint' => '/v1/',
            'debug' => false,
        ]);
        $resolver->setRequired(['client_id', 'client_secret']);

        try {
            $config = $resolver->resolve($options);
        } catch (OptionsResolverException | \Throwable $e) {
            throw InvalidConfiguration::because($e->getMessage(), $e);
        }

        $stack = HandlerStack::create();
        $stack->push(new PersonioAuthentication($config['client_id'], $config['client_secret']));

        $client = new Client([
            'handler' => $stack,
            'base_uri' => rtrim($config['host'], '/').'/'.trim($config['endpoint'], '/').'/',
            'debug' => $config['debug'],
        ]);

        $apiClient = new ApiClient($client);

        self::$apps[$name] = $app = new App($apiClient);

        return $app;
    }

    public static function app(string $name = null): App
    {
        $name = $name ?? self::DEFAULT_APP_NAME;

        if ($name === '') {
            throw InvalidConfiguration::because('Invalid app name "'.$name.'" provided. App name must be a non-empty string.');
        }

        if (!array_key_exists($name, self::$apps)) {
            if (self::DEFAULT_APP_NAME === $name) {
                throw InvalidConfiguration::because('The default app does not exist. Make sure you call initializeApp() first.');
            }

            throw InvalidConfiguration::because('An app named "'.$name.'" does not exist. Make sure you call initializeApp() first.');
        }

        return self::$apps[$name];
    }

    public static function forget(string $name = null): void
    {
        $name = $name ?? self::DEFAULT_APP_NAME;

        unset(self::$apps[$name]);
    }

    public static function reset(): void
    {
        self::$apps = [];
    }
}
