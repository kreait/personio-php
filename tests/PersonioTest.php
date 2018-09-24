<?php

declare(strict_types=1);

namespace Kreait\Personio\Tests;

use Kreait\Personio;
use Kreait\Personio\Error\InvalidConfiguration;
use PHPUnit\Framework\TestCase;

class PersonioTest extends TestCase
{
    protected function tearDown()
    {
        Personio::reset();
    }

    /** @test */
    public function an_initialized_app_can_be_used(): void
    {
        $app = Personio::initializeApp($this->validConfiguration());
        $this->assertSame($app, Personio::app());
    }

    /** @test */
    public function the_configuration_must_be_valid(): void
    {
        $this->expectException(InvalidConfiguration::class);
        Personio::initializeApp();
    }

    /** @test */
    public function the_app_name_must_be_non_empty(): void
    {
        $this->expectException(InvalidConfiguration::class);
        Personio::initializeApp($this->validConfiguration(), '');
    }

    /** @test */
    public function the_default_app_cannot_be_initialized_twice(): void
    {
        Personio::initializeApp($this->validConfiguration());
        $this->expectException(InvalidConfiguration::class);
        Personio::initializeApp($this->validConfiguration());
    }

    /** @test */
    public function a_named_app_cannot_be_initialized_twice(): void
    {
        Personio::initializeApp($this->validConfiguration(), 'foo');
        $this->expectException(InvalidConfiguration::class);
        Personio::initializeApp($this->validConfiguration(), 'foo');
    }

    /** @test */
    public function an_app_must_be_initialized(): void
    {
        $this->expectException(InvalidConfiguration::class);
        Personio::app();
    }

    /** @test */
    public function an_app_cannot_be_accessed_with_an_empty_name(): void
    {
        $this->expectException(InvalidConfiguration::class);
        Personio::app('');
    }

    /** @test */
    public function an_app_can_be_forgotten(): void
    {
        Personio::initializeApp($this->validConfiguration(), 'foo');
        Personio::forget('foo');
        $this->expectException(InvalidConfiguration::class);
        Personio::app('foo');
    }

    /** @test */
    public function personio_can_be_reset(): void
    {
        $apps = ['foo' => null, 'bar' => null];

        foreach (array_keys($apps) as $name) {
            $apps[$name] = Personio::initializeApp($this->validConfiguration(), $name);
        }

        Personio::reset();

        foreach ($apps as $name => $app) {
            try {
                Personio::app($name);
                $this->fail('An error should have been thrown when accessing the app named '.$name);
            } catch (\Throwable $e) {
                $this->assertInstanceOf(InvalidConfiguration::class, $e);
            }
        }
    }

    private function validConfiguration(): array
    {
        return [
            'client_id' => 'client_id',
            'client_secret' => 'client_secret',
        ];
    }
}
