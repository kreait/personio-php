<?php

declare(strict_types=1);

namespace Kreait\Personio;

use DateTimeImmutable;

final class TimeOffPeriod
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $employeeId;

    /**
     * @var TimeOffPeriod\Status
     */
    private $status;

    /**
     * @var TimeOffPeriod\Type
     */
    private $type;

    /**
     * @var DateTimeImmutable
     */
    private $startDate;

    /**
     * @var DateTimeImmutable|null
     */
    private $endDate;

    /**
     * @internal
     */
    public static function fromApiResponse(array $data): self
    {
        $timeOff = new self();
        $timeOff->id = $data['attributes']['id'];
        $timeOff->employeeId = $data['attributes']['employee']['attributes']['id']['value'];
        $timeOff->status = new TimeOffPeriod\Status(strtolower($data['attributes']['status']));
        $timeOff->type = TimeOffPeriod\Type::fromTimeOffApiResponse($data);
        $timeOff->startDate = new DateTimeImmutable($data['attributes']['start_date']);

        if ($endDate = $data['attributes']['end_date'] ?? null) {
            $timeOff->endDate = new DateTimeImmutable($endDate);
        }

        return $timeOff;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function employeeId(): int
    {
        return $this->employeeId;
    }

    public function status(): TimeOffPeriod\Status
    {
        return $this->status;
    }

    public function type(): TimeOffPeriod\Type
    {
        return $this->type;
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }
}
