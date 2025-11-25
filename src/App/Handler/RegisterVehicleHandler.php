<?php

namespace App\App\Handler;

use App\App\Command\RegisterVehicleCommand;
use App\Domain\Repository\FleetRepositoryInterface;
use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\VehicleId;

final class RegisterVehicleHandler
{
    public function __construct(private FleetRepositoryInterface $repository) {}

    public function __invoke(RegisterVehicleCommand $command): void
    {
        $fleetId = new FleetId($command->getFleetId());
        $vehicleId = new VehicleId($command->getVehiclePlateNumber());

        $fleet = $this->repository->find($fleetId);
        $fleet->registerVehicle($vehicleId);
        $this->repository->save($fleet);
    }
}
