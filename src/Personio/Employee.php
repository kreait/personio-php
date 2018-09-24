<?php

declare(strict_types=1);

namespace Kreait\Personio;

/**
 * @todo Remove nullabilities once the API is fixed
 */
class Employee
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $firstName;

    /**
     * @var string|null
     */
    private $lastName;

    /**
     * @var string|null
     */
    private $email;

    private function __construct()
    {
    }

    /**
     * @internal
     */
    public static function fromApiResponse(array $data): self
    {
        $employee = new self();
        $employee->id = $data['attributes']['id']['value'] ?? null;
        $employee->firstName = $data['attributes']['first_name']['value'] ?? null;
        $employee->lastName = $data['attributes']['last_name']['value'] ?? null;
        $employee->email = $data['attributes']['email']['value'] ?? null;

        return $employee;
    }

    /**
     * @internal
     */
    public static function fromTimeOffsApiResponse(array $data): self
    {
        $employee = new self();
        $employee->id = $data['attributes']['employee']['attributes']['id']['value'];
        $employee->firstName = $data['attributes']['employee']['attributes']['first_name']['value'];
        $employee->lastName = $data['attributes']['employee']['attributes']['last_name']['value'];
        $employee->email = $data['attributes']['employee']['attributes']['email']['value'];

        return $employee;
    }

    public function id(): ?int
    {
        // @todo Remove nullability once the API is fixed
        return $this->id;
    }

    public function firstName(): ?string
    {
        return $this->firstName;
    }

    public function lastName(): ?string
    {
        return $this->lastName;
    }

    public function email(): ?string
    {
        return $this->email;
    }
}
