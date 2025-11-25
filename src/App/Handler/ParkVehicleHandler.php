<?php

namespace App\App\Handler;

use App\App\Command\ParkVehicleCommand;
use App\Domain\Repository\FleetRepositoryInterface;
use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\Location;
use App\Domain\ValueObject\VehicleId;

final class ParkVehicleHandler
{
    public function __construct(private FleetRepositoryInterface $repository) {}

    public function __invoke(ParkVehicleCommand $command): void
    {
        $fleetId = new FleetId($command->getFleetId());
        $vehicleId = new VehicleId($command->getVehiclePlateNumber());
        $location = new Location(
            $command->getLatitude(),
            $command->getLongitude(),
            $command->getAltitude()
        );

        $fleet = $this->repository->find($fleetId);
        $fleet->parkVehicle($vehicleId, $location);
        $this->repository->save($fleet);
    }
}
