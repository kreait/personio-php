<?php

declare(strict_types=1);

namespace Kreait\Personio\TimeOffPeriod;

final class Type
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    private function __construct()
    {
    }

    /**
     * @internal
     */
    public static function fromTimeOffApiResponse(array $data): self
    {
        $type = new self();
        $type->id = $data['attributes']['time_off_type']['attributes']['id'];
        $type->name = $data['attributes']['time_off_type']['attributes']['name'];

        return $type;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
