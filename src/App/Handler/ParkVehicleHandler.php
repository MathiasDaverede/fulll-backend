<?php

namespace App\Handler;

use App\Command\ParkVehicleCommand;
use Domain\Exception\FleetNotFoundException;
use Domain\Repository\FleetRepositoryInterface;
use Domain\ValueObject\FleetId;
use Domain\ValueObject\Location;
use Domain\ValueObject\VehiclePlate;

final class ParkVehicleHandler
{
    public function __construct(private FleetRepositoryInterface $fleetRepository) {}

    public function __invoke(ParkVehicleCommand $command): void
    {
        $fleetId = FleetId::generate($command->getFleetId());
        $fleet = $this->fleetRepository->findOneByFleetId($fleetId);

        if (is_null($fleet)) {
            throw new FleetNotFoundException($fleetId);
        }

        $location = new Location(
            $command->getLatitude(),
            $command->getLongitude(),
            $command->getAltitude()
        );

        $fleet->parkVehicle(new VehiclePlate($command->getVehiclePlate()), $location);
        $this->fleetRepository->save($fleet);
    }
}
