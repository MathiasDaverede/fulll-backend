<?php

namespace Infra\Persistence\Mapper;

use Domain\Model\Fleet;
use Domain\ValueObject\FleetId;
use Domain\ValueObject\Location;
use Domain\ValueObject\UserId;
use Infra\Persistence\Entity\FleetEntity;

/**
 * Bidirectional conversion
 * between the Domain Model (Fleet)
 * and the persistence Entity (FleetEntity).
 */
final class FleetMapper
{
    /**
     * Converts a persistence entity (Infra) into a domain aggregate (Domain).
     */
    public static function mapEntityToDomain(FleetEntity $entity): Fleet
    {
        $userId = new UserId($entity->getUserId());
        $fleetId = FleetId::generate($entity->getFleetId());
        $fleet = new Fleet($userId, $fleetId);

        $vehicles = [];

        foreach ($entity->getVehicles() as $plate => $locationData) {
            $location = null;

            if (!empty($locationData)) {
                $location = new Location($locationData['lat'], $locationData['lng'],$locationData['alt']);
            }

            $vehicles[$plate] = $location;
        }

        $fleet->setVehicles($vehicles);

        return $fleet;
    }

    /**
     * Converts a domain aggregate (Domain) into a persistence entity (Infra).
     */
    public static function mapDomainToEntity(Fleet $fleet, FleetEntity $entity): void
    {
        $entity->setUserId($fleet->getUserId()->getValue());
        $entity->setFleetId($fleet->getFleetId()->getValue());
        $entity->setVehicles($fleet->getVehicles());

        $persistenceVehicles = [];

        foreach ($fleet->getVehicles() as $plate => $location) {
            $persistenceLocation = null;

            if ($location instanceof Location) {
                $persistenceLocation = [
                    'lat' => $location->getLatitude(),
                    'lng' => $location->getLongitude(),
                    'alt' => $location->getAltitude(),
                ];
            }

            $persistenceVehicles[$plate] = $persistenceLocation;
        }

        $entity->setVehicles($persistenceVehicles);
    }
}
