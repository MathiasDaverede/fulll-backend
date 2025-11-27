<?php

namespace App\QueryHandler;

use App\Query\GetVehicleLocationQuery;
use Domain\Exception\FleetNotFoundException;
use Domain\Repository\FleetRepositoryInterface;
use Domain\ValueObject\FleetId;
use Domain\ValueObject\Location;
use Domain\ValueObject\VehiclePlate;

final class GetVehicleLocationHandler
{
    public function __construct(private FleetRepositoryInterface $fleetRepository) {}

    public function __invoke(GetVehicleLocationQuery $query): ?Location
    {
        $fleetId = FleetId::generate($query->getFleetId());
        $fleet = $this->fleetRepository->findOneByFleetId($fleetId);

        if (is_null($fleet)) {
            throw new FleetNotFoundException($fleetId);
        }

        return $fleet->getVehicleLocation(new VehiclePlate($query->getVehiclePlate()));
    }
}
