<?php

namespace App\QueryHandler;

use App\Query\GetFleetVehiclesQuery;
use Domain\Exception\FleetNotFoundException;
use Domain\Repository\FleetRepositoryInterface;
use Domain\ValueObject\FleetId;
use Domain\ValueObject\Location;

final class GetFleetVehicleHandler
{
    public function __construct(private FleetRepositoryInterface $fleetRepository) {}

    /**
     * @return array<string, Location>
     */
    public function __invoke(GetFleetVehiclesQuery $query): array
    {
        $fleetId = FleetId::generate($query->getFleetId());
        $fleet = $this->fleetRepository->findOneByFleetId($fleetId);

        if (is_null($fleet)) {
            throw new FleetNotFoundException($fleetId);
        }

        return $fleet->getVehicles();
    }
}
