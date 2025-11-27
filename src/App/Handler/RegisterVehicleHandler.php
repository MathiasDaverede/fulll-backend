<?php

namespace App\Handler;

use App\Command\RegisterVehicleCommand;
use Domain\Exception\FleetNotFoundException;
use Domain\Repository\FleetRepositoryInterface;
use Domain\ValueObject\FleetId;
use Domain\ValueObject\VehiclePlate;

final class RegisterVehicleHandler
{
    public function __construct(private FleetRepositoryInterface $fleetRepository) {}

    public function __invoke(RegisterVehicleCommand $command): void
    {
        $fleetId = FleetId::generate($command->getFleetId());
        $fleet = $this->fleetRepository->findOneByFleetId($fleetId);

        if (is_null($fleet)) {
            throw new FleetNotFoundException($fleetId);
        }

        $fleet->registerVehicle(new VehiclePlate($command->getVehiclePlate()));
        $this->fleetRepository->save($fleet);
    }
}
