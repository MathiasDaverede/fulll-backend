<?php

namespace App\Infra\Persistence;

use App\App\Exception\FleetNotFoundException;
use App\Domain\Model\Fleet;
use App\Domain\Repository\FleetRepositoryInterface;
use App\Domain\ValueObject\FleetId;

final class InMemoryFleetRepository implements FleetRepositoryInterface
{
    /** @var array<string, Fleet> */
    private array $fleets = [];

    public function save(Fleet $fleet): void
    {
        $this->fleets[$fleet->getFleetId()->getValue()] = $fleet;
    }

    public function find(FleetId $fleetId): Fleet
    {   
        if (!isset($this->fleets[$fleetId->getValue()])) {
            throw new FleetNotFoundException($fleetId);
        }

        return $this->fleets[$fleetId->getValue()]; 
    }

    public function reset(): void
    {
        $this->fleets = [];
    }
}
