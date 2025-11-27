<?php

namespace App\Handler;

use App\Command\CreateFleetCommand;
use Domain\Repository\FleetRepositoryInterface;
use Domain\ValueObject\UserId;

final class CreateFleetHandler
{
    public function __construct(private FleetRepositoryInterface $fleetRepository) {}

    public function __invoke(CreateFleetCommand $command): string
    {
        $fleet = $this->fleetRepository->create(new UserId($command->getUserId()));

        return $fleet->getFleetId()->getValue();
    }
}
