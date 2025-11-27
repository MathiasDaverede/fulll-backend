<?php

namespace Domain\Exception;

use Domain\ValueObject\Location;
use Domain\ValueObject\VehiclePlate;
use DomainException;

final class VehicleAlreadyParkedException extends DomainException
{
    public static function atLocation(VehiclePlate $vehiclePlate, Location $location): self
    {
        return new self(sprintf(
            'Vehicle "%s" is already parked at location "%s, %s, %s".',
            $vehiclePlate->getValue(),
            $location->getLatitude(),
            $location->getLongitude(),
            $location->getAltitude()
        ));
    }
}
