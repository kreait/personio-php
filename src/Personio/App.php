<?php

declare(strict_types=1);

namespace Kreait\Personio;

use Kreait\Personio\Error\NotFound;
use Tightenco\Collect\Support\Collection;

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

    /**
     * @return Collection|Employee[]
     */
    public function getEmployees()
    {
        /** @var Collection $employees */
        $employees = $this->client->getEmployees()
            ->then(function (array $data) {
                return collect($data)
                    ->map(function (array $data) {
                        return Employee::fromApiResponse($data);
                    })->values();
            })->wait();

        // The employees API endpoint currently returns empty attribute arrays,
        // so we fake it 'til they make it
        // @todo Check back if the API is fixed
        $filtered = $employees->filter(function (Employee $employee) {
            return $employee->id() !== null;
        });

        if ($filtered->isNotEmpty()) {
            return $employees;
        }

        return $this->getParsedEmployeesFromTimeOffs();
    }

    public function getEmployee(int $id): Employee
    {
        // The employees API endpoint currently returns empty attribute arrays,
        // so we fake it 'til they make it
        // @todo Check back if the API is fixed
        $employee = $this->getEmployees()
            ->filter(function (Employee $employee) use ($id) {
                return $employee->id() === $id;
            })->first();

        if ($employee) {
            return $employee;
        }

        throw NotFound::because("No employee with ID {$id} found.");
    }

    /**
     * @return Collection|TimeOffPeriod[]
     */
    public function getTimeOffPeriods()
    {
        return $this->client->getTimeOffs()
            ->then(function (array $data) {
                return collect($data)
                    ->map(function (array $data) {
                        return TimeOffPeriod::fromApiResponse($data);
                    })->values();
            })->wait();
    }

    public function getTimeOffPeriod(int $id): TimeOffPeriod
    {
        return $this->client->getTimeOff($id)
            ->then(function ($data) {
                return TimeOffPeriod::fromApiResponse($data);
            }, function (\Throwable $e) use ($id) {
                if ($e instanceof NotFound) {
                    throw new NotFound("No time off with ID {$id} found.");
                }

                throw $e;
            })->wait();
    }

    /**
     * @return Collection|\Kreait\Personio\TimeOffPeriod\Type[]
     */
    public function getTimeOffPeriodTypes()
    {
        // @todo There's no documented time off types endpoint, so we're faking it until there is
        return $this->getTimeOffPeriods()
            ->map(function (TimeOffPeriod $timeOff) {
                return $timeOff->type();
            })
            ->unique(function (TimeOffPeriod\Type $type) {
                return $type->id();
            })
            ->sortBy(function (TimeOffPeriod\Type $type) {
                return $type->name();
            })
            ->values();
    }

    private function getParsedEmployeesFromTimeOffs()
    {
        return $this->client->getTimeOffs()
            ->then(function (array $data) {
                return collect($data)
                    ->map(function (array $data) {
                        return Employee::fromTimeOffsApiResponse($data);
                    })
                    ->unique(function (Employee $employee) {
                        return $employee->id();
                    })
                    ->sortBy(function (Employee $employee) {
                        return strtolower($employee->lastName().$employee->firstName());
                    })
                    ->values();
            })->wait();
    }
}
