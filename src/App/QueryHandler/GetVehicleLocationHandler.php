<?php

namespace App\App\QueryHandler;

use App\App\Query\GetVehicleLocationQuery;
use App\Domain\Repository\FleetRepositoryInterface;
use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\Location;
use App\Domain\ValueObject\VehicleId;

final class GetVehicleLocationHandler
{
    public function __construct(private FleetRepositoryInterface $repository) {}

    public function __invoke(GetVehicleLocationQuery $query): ?Location
    {
        $fleetId = new FleetId($query->getFleetId());
        $vehicleId = new VehicleId($query->getVehiclePlateNumber());

        $fleet = $this->repository->find($fleetId);

        return $fleet->getVehicleLocation($vehicleId);
    }
}
